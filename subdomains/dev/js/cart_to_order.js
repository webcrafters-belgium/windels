
(function(){
    // ===== Helpers =====
    const byId  = id => document.getElementById(id);
    const qSel  = (s, r=document) => r.querySelector(s);
    const setHidden = (el,flag) => el && el.classList.toggle('hidden', !!flag);
    const fmtEuro = v => new Intl.NumberFormat('nl-BE',{style:'currency',currency:'EUR'}).format(v);

    // ===== Config uit data-attributen =====
    const root        = document.querySelector('.checkout-wrap');
    const SHOP_ADDRESS= root?.dataset.shopAddress || '';
    const SUMMER_HOURS= root?.dataset.summerHours ? JSON.parse(root.dataset.summerHours) : {};
    const WINTER_HOURS= root?.dataset.winterHours ? JSON.parse(root.dataset.winterHours) : {};
    const EXCEPTIONS  = root?.dataset.exceptions ? JSON.parse(root.dataset.exceptions) : {};
    const IS_NL_PARTNER = (root?.dataset.isNlPartner === '1');
    const VAT_RATE    = IS_NL_PARTNER ? 0 : 0.21;
    const MAX_KM      = 50;

    // ===== DOM refs =====
    const klantAdres = byId('klant_adres');
    const landSel    = byId('klant_land');
    const btnCalc    = byId('btn_calc_distance');
    const distanceStatus = byId('distance_status');
    const distanceDisplayAshes = byId('distance_display_ashes');
    const distanceWarningAshes = byId('distance_warning_ashes');

    const ashesSelfInfo   = byId('ashes_self_info');
    const ashesCollectBox = byId('ashes_collect_box');
    const ashesCollectDate= byId('ashes_collect_date');
    const ashesCollectTime= byId('ashes_collect_time');
    const ashesCourierNote= byId('ashes_courier_note');

    const selfOpeningHoursList     = byId('self_opening_hours');
    const finishedOpeningHoursList = byId('finished_opening_hours');

    const finishedPickupInfo = byId('finished_pickup_info');
    const finishedDeliveryBox= byId('finished_delivery_box');

    const distanceDisplay = byId('distance_display');
    const distanceWarning = byId('distance_warning');
    const manualWrap      = byId('manual_distance_wrap');
    const manualKmEl      = byId('manual_distance_km');

    const deliveryTariffText = byId('delivery_tariff_text');

    const finishedFeeHidden= byId('finished_delivery_fee');
    const feeBadgeAshes     = byId('fee_badge_ashes');
    const feeVatAshes       = byId('fee_vat_ashes');
    const feeBadgeFinished  = byId('fee_badge_finished');
    const feeVatFinished    = byId('fee_vat_finished');
    const finishedTotalRow  = byId('finished_total_row');
    const finishedTotalFee  = byId('finished_total_fee');

    const summaryDeliveryLines   = byId('summary_delivery_lines');
    const summaryDeliveryAmount  = byId('summary_delivery_amount');
    const summaryDeliveryVatLine = byId('summary_delivery_vat_line');
    const summaryVatDelivery     = byId('summary_vat_delivery');
    const summaryVatTotal        = byId('summary_vat_total');
    const summaryNetTotal        = byId('summary_net_total');
    const grandTotalEl           = byId('grand_total');

    const productsTotal = parseFloat(byId('order_products_total').value || '0');
    const vatProducts   = parseFloat(byId('vat_products_total').value   || '0');

    const deliveryCostTotalHidden = byId('delivery_cost_total');
    const vatDeliveryHidden       = byId('vat_delivery_total');
    const vatOrderHidden          = byId('vat_order_total');
    const netOrderHidden          = byId('net_order_total');
    const distanceRawHidden       = byId('distance_km_raw');
    const distanceCappedHidden    = byId('distance_km_capped');
    const distanceMethodHidden    = byId('distance_method');
    const outOfZoneFlag           = byId('out_of_zone_flag');

    // ===== Utils =====
    function vatFromGross(g){
        if (IS_NL_PARTNER) return 0;
        return g * (VAT_RATE / (1 + VAT_RATE));
    }
    function getSeasonHoursTableForMonth(m){
        return (m >= 6 && m <= 10) ? SUMMER_HOURS : WINTER_HOURS;
    }
    function getExceptionForDate(dateStr){
        if (!dateStr) return null;
        return (EXCEPTIONS && EXCEPTIONS[dateStr]) ? EXCEPTIONS[dateStr] : null;
    }
    function isExceptionClosed(dateStr){
        const ex = getExceptionForDate(dateStr);
        return !!(ex && String(ex.status).toLowerCase() === 'gesloten');
    }
    function setExceptionMessage(dateStr){
        const box = byId('exception_msg');
        if (!box) return;
        const ex = getExceptionForDate(dateStr);
        if (!ex){
            box.classList.add('hidden');
            box.textContent = '';
            return;
        }
        if (ex.status === 'gesloten'){
            box.classList.remove('hidden');
            box.innerHTML = '<b>Uitzonderlijk gesloten</b>. De datum is verschoven naar de eerstvolgende openingsdag.';
        } else if (ex.status === 'open' && ex.start && ex.end){
            box.classList.remove('hidden');
            box.innerHTML = 'Uitzonderlijk <b>open</b> met aangepaste openingsuren: <b>' + ex.start + '–' + ex.end + '</b>.';
        } else {
            box.classList.add('hidden');
            box.textContent = '';
        }
    }
    function getHoursForDateStr(dateStr){
        if (!dateStr) return '';
        const ex = getExceptionForDate(dateStr);
        if (ex){
            if (ex.status === 'gesloten') return '';
            if (ex.status === 'open' && ex.start && ex.end) return ex.start + '–' + ex.end;
        }
        const d = new Date(dateStr + 'T00:00:00');
        if (isNaN(d)) return '';
        const m  = d.getMonth() + 1;
        const wd = d.getDay();
        const table = getSeasonHoursTableForMonth(m);
        return table[wd] || '';
    }
    function parseHoursRange(str){
        if (!str || typeof str !== 'string' || !str.includes('–')) return null;
        const parts = str.split('–');
        if (parts.length !== 2) return null;
        const a = parts[0].trim();
        const b = parts[1].trim();
        if (!/^\d{2}:\d{2}$/.test(a) || !/^\d{2}:\d{2}$/.test(b)) return null;
        return { start: a, end: b };
    }
    function roundToStep(timeStr, stepSeconds){
        const [hh,mm] = timeStr.split(':').map(x => parseInt(x,10));
        let secs = hh*3600 + mm*60;
        const r  = Math.round(secs / stepSeconds) * stepSeconds;
        const H  = Math.floor(r/3600);
        const M  = Math.floor((r%3600)/60);
        return (H<10?'0':'')+H+':' + (M<10?'0':'')+M;
    }

    // ===== Datum/uur as-afhalen =====
    const TIME_STEP_SEC = 900;

    function disableTimePicker(){
        if (!ashesCollectTime) return;
        ashesCollectTime.value   = '';
        ashesCollectTime.disabled= true;
        ashesCollectTime.min     = '';
        ashesCollectTime.max     = '';
        ashesCollectTime.step    = TIME_STEP_SEC;
    }

    function setTimeConstraintsForDate(dateStr){
        if (!ashesCollectTime) return false;
        if (isExceptionClosed(dateStr)){
            disableTimePicker();
            setExceptionMessage(dateStr);
            return false;
        }
        const range = parseHoursRange(getHoursForDateStr(dateStr));
        if (!range){
            disableTimePicker();
            setExceptionMessage(dateStr);
            return false;
        }
        setExceptionMessage(dateStr);
        ashesCollectTime.disabled = false;
        ashesCollectTime.step     = TIME_STEP_SEC;
        ashesCollectTime.min      = range.start;
        ashesCollectTime.max      = range.end;
        if (!ashesCollectTime.value ||
            ashesCollectTime.value < range.start ||
            ashesCollectTime.value > range.end){
            ashesCollectTime.value = roundToStep(range.start, TIME_STEP_SEC);
        }
        return true;
    }

    function ensureValidPickupDateAndTime(){
        if (!ashesCollectDate) return;
        const todayStr = new Date().toISOString().slice(0,10);
        if (!ashesCollectDate.min) {
            ashesCollectDate.min = todayStr;
        }
        if (ashesCollectDate.value && ashesCollectDate.value < todayStr) {
            ashesCollectDate.value = todayStr;
        }
        setTimeConstraintsForDate(ashesCollectDate.value || todayStr);
    }

    // ===== Opening hours render =====
    function renderOpeningHoursInto(targetUl){
        if (!targetUl) return;
        const m   = (new Date()).getMonth() + 1;
        const tbl = getSeasonHoursTableForMonth(m);
        const labels = ['Zon','Maa','Din','Woe','Don','Vri','Zat'];
        targetUl.innerHTML = '';
        for (let i=1;i<=6;i++){
            const li = document.createElement('li');
            li.textContent = labels[i] + ': ' + (tbl[i] || 'gesloten');
            targetUl.appendChild(li);
        }
    }

    // ===== UI toggles =====
    function updateAshesUI(){
        const v = (qSel('input[name="ashes_delivery_method"]:checked') || {}).value;
        setHidden(ashesSelfInfo, v !== 'zelf_bezorgen');
        if (v === 'zelf_bezorgen') {
            renderOpeningHoursInto(selfOpeningHoursList);
        }
        setHidden(ashesCollectBox, v !== 'afgehaald_door_ons');
        if (v === 'afgehaald_door_ons'){
            ensureValidPickupDateAndTime();
        } else {
            if (ashesCollectDate) ashesCollectDate.value = '';
            if (ashesCollectTime){
                ashesCollectTime.value   = '';
                ashesCollectTime.disabled= false;
                ashesCollectTime.min     = '';
                ashesCollectTime.max     = '';
            }
            setExceptionMessage('');
        }
        setHidden(ashesCourierNote, v !== 'koerier');
    }

    function updateFinishedUI(){
        const am = (qSel('input[name="ashes_delivery_method"]:checked') || {}).value;
        const fm = (qSel('input[name="finished_delivery_method"]:checked') || {}).value;
        setHidden(finishedPickupInfo, fm !== 'afhalen_winkel');
        if (fm === 'afhalen_winkel'){
            renderOpeningHoursInto(finishedOpeningHoursList);
        }
        setHidden(finishedDeliveryBox, fm !== 'bezorgen');
        if (fm !== 'bezorgen'){
            if (am === 'afgehaald_door_ons') {
                refreshDistanceAndTotalsIfNeeded();
            } else {
                applyDelivery(0,'');
            }
        }
    }

    // ===== Tarief/levering =====
    function getRatePer10Km(){ return 3.80; }

    function feeFromKm(km, ratePer10){
        if (!km || km <= 0) return 0;
        const capped = Math.min(km, MAX_KM);
        const rate   = ratePer10 || 3.80;
        const fee    = (capped / 10) * rate; // pro rata
        return Math.round((fee + Number.EPSILON) * 100) / 100;
    }

    function applyDelivery(distanceKm, method){
        const raw       = Math.max(0, distanceKm || 0);
        const outOfZone = raw > MAX_KM;
        const capped    = Math.min(raw, MAX_KM);
        const rate      = getRatePer10Km();

        const am = (qSel('input[name="ashes_delivery_method"]:checked') || {}).value;
        const fm = (qSel('input[name="finished_delivery_method"]:checked') || {}).value;

        // Buiten zone
        if (outOfZone){
            if (distanceDisplay)      distanceDisplay.textContent      = raw.toFixed(1) + ' km';
            if (distanceDisplayAshes) distanceDisplayAshes.textContent = raw.toFixed(1) + ' km';

            if (distanceWarning){
                distanceWarning.style.display = (fm === 'bezorgen') ? 'block' : 'none';
                if (fm === 'bezorgen'){
                    distanceWarning.innerHTML = 'Buiten leveringsgebied: we leveren maximaal <b>50 km</b> vanaf de winkel.';
                }
            }
            if (distanceWarningAshes){
                if (am === 'afgehaald_door_ons'){
                    distanceWarningAshes.classList.remove('hidden');
                    distanceWarningAshes.innerHTML = 'Buiten afhaalsgebied: we komen afhalen maximaal <b>50 km</b> vanaf de winkel.';
                } else {
                    distanceWarningAshes.classList.add('hidden');
                }
            }

            if (summaryDeliveryLines)   summaryDeliveryLines.classList.add('hidden');
            if (summaryDeliveryVatLine) summaryDeliveryVatLine.classList.add('hidden');

            if (feeBadgeAshes)    feeBadgeAshes.textContent    = fmtEuro(0);
            if (feeVatAshes)      feeVatAshes.textContent      = fmtEuro(0);
            if (feeBadgeFinished) feeBadgeFinished.textContent = fmtEuro(0);
            if (feeVatFinished)   feeVatFinished.textContent   = fmtEuro(0);
            if (finishedTotalRow) finishedTotalRow.classList.add('hidden');
            if (finishedTotalFee) finishedTotalFee.textContent = fmtEuro(0);

            deliveryCostTotalHidden.value = '0.00';
            vatDeliveryHidden.value       = '0.00';
            finishedFeeHidden.value       = '0.00';
            distanceRawHidden.value       = raw.toFixed(3);
            distanceCappedHidden.value    = capped.toFixed(3);
            if (method) distanceMethodHidden.value = method;
            if (outOfZoneFlag) outOfZoneFlag.value = '1';

            const vatTotal = parseFloat(byId('vat_products_total').value || '0');
            const grand    = parseFloat(byId('order_products_total').value || '0');
            const net      = grand - vatTotal;

            grandTotalEl.textContent    = fmtEuro(grand);
            summaryVatTotal.textContent = fmtEuro(vatTotal);
            summaryNetTotal.textContent = fmtEuro(net);
            return;
        } else {
            if (outOfZoneFlag) outOfZoneFlag.value = '0';
            if (distanceWarning)      distanceWarning.style.display = 'none';
            if (distanceWarningAshes) distanceWarningAshes.classList.add('hidden');
        }

        // Normale berekening
        const oneWayFee   = feeFromKm(capped, rate);
        const feeAshes    = (am === 'afgehaald_door_ons') ? oneWayFee : 0;
        const feeFinished = (fm === 'bezorgen') ? oneWayFee : 0;
        const feeTotal    = feeAshes + feeFinished;
        const bothSelected= (am === 'afgehaald_door_ons' && fm === 'bezorgen');

        if (distanceDisplay)      distanceDisplay.textContent      = capped.toFixed(1) + ' km';
        if (distanceDisplayAshes) distanceDisplayAshes.textContent = capped.toFixed(1) + ' km';

        if (feeBadgeAshes)    feeBadgeAshes.textContent    = fmtEuro(feeAshes);
        if (feeVatAshes)      feeVatAshes.textContent      = fmtEuro(vatFromGross(feeAshes));
        if (feeBadgeFinished) feeBadgeFinished.textContent = fmtEuro(feeFinished);
        if (feeVatFinished)   feeVatFinished.textContent   = fmtEuro(vatFromGross(feeFinished));

        if (finishedTotalRow) finishedTotalRow.classList.toggle('hidden', !bothSelected);
        if (finishedTotalFee) finishedTotalFee.textContent = fmtEuro(feeTotal);

        const vatDelivery = vatFromGross(feeTotal);
        const vatTotal    = (parseFloat(byId('vat_products_total').value || '0')) + vatDelivery;
        const grand       = (parseFloat(byId('order_products_total').value || '0')) + feeTotal;
        const net         = grand - vatTotal;

        if (summaryDeliveryLines)   summaryDeliveryLines.classList.toggle('hidden', !(feeTotal > 0));
        if (summaryDeliveryVatLine) summaryDeliveryVatLine.classList.toggle('hidden', !(feeTotal > 0));
        if (summaryDeliveryAmount)  summaryDeliveryAmount.textContent = fmtEuro(feeTotal);
        if (summaryVatDelivery)     summaryVatDelivery.textContent    = fmtEuro(vatDelivery);

        grandTotalEl.textContent    = fmtEuro(grand);
        summaryVatTotal.textContent = fmtEuro(vatTotal);
        summaryNetTotal.textContent = fmtEuro(net);

        deliveryCostTotalHidden.value = feeTotal.toFixed(2);
        vatDeliveryHidden.value       = vatDelivery.toFixed(2);
        finishedFeeHidden.value       = feeTotal.toFixed(2);
        distanceRawHidden.value       = raw.toFixed(3);
        distanceCappedHidden.value    = capped.toFixed(3);
        if (method) distanceMethodHidden.value = method;
    }

    // ===== Afstand via proxy =====
    async function calcDistanceGoogle(){
        const addrCustomer = (klantAdres.value || '').trim();
        if (!addrCustomer){
            distanceStatus.textContent = 'Geen klantadres ingevuld';
            return { km:0, method:null };
        }

        const country = (landSel?.value || 'BE').toUpperCase();
        distanceStatus.textContent = 'Afstand berekenen…';

        try {
            const params = new URLSearchParams({
                origin: SHOP_ADDRESS,
                destination: addrCustomer,
                country: country,
                mode: 'driving'
            });

            const res  = await fetch('/api/haversine_distance.php?' + params.toString(), { cache:'no-store' });
            const text = await res.text();
            let data;

            try {
                data = JSON.parse(text);
            } catch (e){
                console.error('distance raw response', text);
                distanceStatus.textContent = 'Afstand via server mislukt (' + res.status + ') – geen geldige JSON.';
                return { km:0, method:null };
            }

            console.log('distance API response', data);

            if (!data.ok || typeof data.km !== 'number'){
                distanceStatus.textContent = 'Afstand fout: ' + (data.error || 'onbekend') + ' – vul handmatig km in.';
                return { km:0, method:null };
            }

            distanceStatus.textContent = 'Rijafstand (one-way): ' + data.km.toFixed(1) + ' km';
            return { km:data.km, method:'google_driving_proxy' };

        } catch (e){
            console.error('distance error', e);
            distanceStatus.textContent = 'Afstand berekenen mislukt – vul handmatig km in.';
            return { km:0, method:null };
        }
    }

    async function calcDistanceAPI() {
        const raw = (klantAdres.value || '').trim();
        if (!raw) {
            distanceStatus.textContent = 'Geen klantadres ingevuld';
            return { km: 0, method: null };
        }

        // 1) Extract adres + postcode + stad
        // Voorbeeld: "Kleine Fonteinstraat 9, 3950 Bocholt"
        const m = raw.match(/^(.+?)\s*,?\s*(\d{4})\s+(.+)$/i);
        if (!m) {
            distanceStatus.textContent = 'Adresformaat ongeldig. Gebruik: Straat 1, 3950 Gemeente.';
            return { km: 0, method: null };
        }

        const address = m[1].trim();     // straat + nr
        const zipcode = m[2].trim();     // 4 cijfers
        const city    = m[3].trim();     // gemeente

        distanceStatus.textContent = 'Afstand berekenen…';

        try {
            const res = await fetch('/api/haversine_distance.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                cache: 'no-store',
                body: JSON.stringify({ address, zipcode, city })
            });

            const data = await res.json();

            if (!data.success || typeof data.distance !== 'number') {
                distanceStatus.textContent = 'Adres niet gevonden – vul handmatig in.';
                return { km: 0, method: null };
            }

            const km = data.distance;
            distanceStatus.textContent = 'Rijafstand (one-way): ' + km.toFixed(1) + ' km';

            return { km, method: 'haversine_osm' };

        } catch (e) {
            console.error(e);
            distanceStatus.textContent = 'Afstand mislukt – vul handmatig in.';
            return { km: 0, method: null };
        }
    }


    async function refreshDistanceAndTotalsIfNeeded(){
        const am = (qSel('input[name="ashes_delivery_method"]:checked') || {}).value;
        const fm = (qSel('input[name="finished_delivery_method"]:checked') || {}).value;
        const needsDistance = (fm === 'bezorgen') || (am === 'afgehaald_door_ons');
        if (!needsDistance){
            applyDelivery(0,'');
            return;
        }

        if (distanceWarning)      distanceWarning.style.display = 'none';
        if (distanceWarningAshes) distanceWarningAshes.classList.add('hidden');
        if (manualWrap)           manualWrap.classList.add('hidden');

        const result = await calcDistanceAPI();
        const km     = result?.km || 0;

        if (km > 0){
            applyDelivery(km, result.method);
            return;
        }

        // fallback handmatig
        if (fm === 'bezorgen' && distanceWarning){
            distanceWarning.style.display = 'block';
            distanceWarning.innerHTML = 'Afstand kon niet automatisch berekend worden. Vul onderstaande <b>handmatige afstand</b> in.';
        }
        if (am === 'afgehaald_door_ons' && distanceWarningAshes){
            distanceWarningAshes.classList.remove('hidden');
            distanceWarningAshes.innerHTML = 'Afstand kon niet automatisch berekend worden. <b>Bereken opnieuw</b> of vul handmatig onderaan in.';
        }
        if (manualWrap) manualWrap.classList.remove('hidden');

        const manualVal = parseFloat(manualKmEl?.value || '');
        if (manualVal > 0){
            distanceStatus.textContent = 'Handmatige afstand (one-way): ' + manualVal.toFixed(1) + ' km';
            applyDelivery(manualVal, 'manual_input');
        } else {
            applyDelivery(0,'');
        }
    }

    // ===== Listeners =====
    document.querySelectorAll('input[name="ashes_delivery_method"]').forEach(r =>
        r.addEventListener('change', () => {
            updateAshesUI();
            refreshDistanceAndTotalsIfNeeded();
        })
    );
    document.querySelectorAll('input[name="finished_delivery_method"]').forEach(r =>
        r.addEventListener('change', () => {
            updateFinishedUI();
            refreshDistanceAndTotalsIfNeeded();
        })
    );

    if (ashesCollectDate){
        ashesCollectDate.addEventListener('change', () => {
            setTimeConstraintsForDate(ashesCollectDate.value);
        });
    }
    if (ashesCollectTime){
        ashesCollectTime.addEventListener('change', () => {
            const range = parseHoursRange(getHoursForDateStr(ashesCollectDate?.value));
            if (range){
                if (ashesCollectTime.value < range.start) ashesCollectTime.value = range.start;
                if (ashesCollectTime.value > range.end)   ashesCollectTime.value = range.end;
            }
        });
    }

    if (btnCalc)    btnCalc.addEventListener('click', refreshDistanceAndTotalsIfNeeded);
    if (klantAdres) klantAdres.addEventListener('blur', refreshDistanceAndTotalsIfNeeded);
    if (landSel)    landSel.addEventListener('change', refreshDistanceAndTotalsIfNeeded);
    if (manualKmEl) manualKmEl.addEventListener('input', refreshDistanceAndTotalsIfNeeded);

    // ===== Init =====
    updateAshesUI();
    updateFinishedUI();
    if ((qSel('input[name="ashes_delivery_method"]:checked') || {}).value === 'zelf_bezorgen'){
        renderOpeningHoursInto(selfOpeningHoursList);
    }
    if ((qSel('input[name="finished_delivery_method"]:checked') || {}).value === 'afhalen_winkel'){
        renderOpeningHoursInto(finishedOpeningHoursList);
    }
    if (ashesCollectDate?.value){
        setTimeConstraintsForDate(ashesCollectDate.value);
    }

    // ===== Submit-validatie =====
    document.getElementById('order-form').addEventListener('submit', async function(e){
        const am = (qSel('input[name="ashes_delivery_method"]:checked') || {}).value;
        const fm = (qSel('input[name="finished_delivery_method"]:checked') || {}).value;

        if (am === 'afgehaald_door_ons'){
            const ds = ashesCollectDate?.value || '';
            if (!ds){
                e.preventDefault();
                alert('Gelieve een datum te kiezen wanneer wij de as mogen afhalen.');
                return;
            }
            if (isExceptionClosed(ds)){
                e.preventDefault();
                alert('De gekozen datum is uitzonderlijk gesloten. Kies een andere datum.');
                return;
            }
            const range = parseHoursRange(getHoursForDateStr(ds));
            if (!range){
                e.preventDefault();
                alert('De gekozen datum valt op een dag dat we gesloten zijn. Kies een andere dag aub.');
                return;
            }
            if (!ashesCollectTime?.value){
                e.preventDefault();
                alert('Gelieve een uur te kiezen wanneer wij de as mogen afhalen.');
                return;
            }
            if (ashesCollectTime.value < range.start || ashesCollectTime.value > range.end){
                e.preventDefault();
                alert('Het gekozen uur ligt buiten de openingsuren. Kies een uur tussen ' + range.start + ' en ' + range.end + '.');
                return;
            }
        }

        const needsDistance = (fm === 'bezorgen') || (am === 'afgehaald_door_ons');
        if (needsDistance && !(parseFloat(distanceCappedHidden.value || '0') > 0)){
            e.preventDefault();
            await refreshDistanceAndTotalsIfNeeded();
            if (!(parseFloat(distanceCappedHidden.value || '0') > 0)){
                alert('Afstand niet beschikbaar. Vul handmatig de afstand (km) in of controleer het adres.');
                return;
            }
        }

        const rawKm = parseFloat(distanceRawHidden.value || '0');
        if ((fm === 'bezorgen' || am === 'afgehaald_door_ons') && rawKm > MAX_KM){
            e.preventDefault();
            alert('Buiten leveringsgebied: we leveren maximaal 50 km vanaf de winkel.');
            return;
        }
        if (outOfZoneFlag && outOfZoneFlag.value === '1'){
            e.preventDefault();
            alert('Buiten leveringsgebied: we leveren maximaal 50 km vanaf de winkel.');
            return;
        }
    });
})();


