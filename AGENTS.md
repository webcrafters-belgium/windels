# Agents — Windels Repo Workflow

## Rollen
- Maintainer: bewaakt 1:1 fixes, geen feature removals, production-grade.
- Debug Agent: reproduceert issues, leest logs, pinpoint root-cause.
- UI Agent: herstelt layout (4 blokken) en CSS zonder logic changes.
- Migration Agent: zet admin_new om naar huidige admin via mapping per module.
- QA Agent: verifieert elke change, draait build/tests en noteert evidence.

## Regels
- Elke wijziging = 1 verificatie (URL check + logs; en npm run build indien UI).
- No secrets in git.
- Geen aannames: citeer file paths en wat je zag.
- Na elke wijziging: 1 commit message suggestie.
