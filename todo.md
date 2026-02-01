# TODO — Windels Admin herstel & migratie

## P0 — Admin terug werkend
- [ ] Discovery: identificeer entrypoint + routing (paths + files)
- [ ] Reproduceer fout + noteer exacte error/stacktrace
- [ ] Fix blocker (minimale patch, geen removals)
- [ ] Verifieer: admin URL 200 + dashboard render
- [ ] Verifieer: login (indien relevant)
- [ ] Build check (indien frontend): npm run build

## P1 — Oude dashboard layout terug (4 blokken)
- [ ] Vind oude layout bron (files/commit/branch)
- [ ] Restore HTML/CSS 1:1
- [ ] Verifieer UI + build

## P2 — admin_new -> huidige admin (1:1)
- [ ] Inventaris admin_new pages/modules
- [ ] Mapping naar huidige admin
- [ ] Migratie per module (met verify per stap)
- [ ] Cleanup zonder removals (alleen dode links na migratie fixen)

## P3 — Afwerking
- [ ] Smoke test flows
- [ ] Documenteer run/deploy stappen kort
- [ ] Final build + commit
