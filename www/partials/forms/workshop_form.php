<form id="workshop-form" method="post">
    <input type="text" name="name" placeholder="Naam" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="number" name="persons" max="12" min="1" value="1">
    <input type="date" name="date" required>
    <select name="timeslot">
        <option value="18:30-23:00">18:30 – 23:00</option>
        <option value="10:00-18:00">10:00 – 18:00 (enkel zondag)</option>
    </select>
    <button type="submit">Inschrijven</button>
</form>
