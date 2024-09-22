# Mugsy

This repo has been reset and refactored to contain a monorepo of all Mugsy services. 

🚧**This update is in progress and should not be considered complete at this time.** 🚧

You can find Mugsy's individual repos, CAD files, PCB files, and more at the official Org site on Github:  https://github.com/MugsyOS



## Applications

### Operator
Operator is a FastAPI application designed to manage and control Mugsys hardware devices. All GPIO control is handled by Operator to keep hardware related functionality isolated from the primary DECAF API, allowing for increased hardware compatibility.

Until this monorepo refactor is completed, you can find dev and in progress feature branches on the Org repo: https://github.com/MugsyOS/Operator

![CustomTag](https://img.shields.io/badge/Python-FastAPI-purple)

### DECAF

DECAF is Mugsy's primary REST API. It handles all the non-hardware related functionality like users, recipes, integrations and tons more. DECAF also manages the brewing process through communications with Operator.

DECAF works with Mugsy's local SQLite database, using [Better-SQLite-3](https://github.com/WiseLibs/better-sqlite3), and [Drizzle](https://orm.drizzle.team/docs/overview) for a comfy dev experience.

![](https://img.shields.io/badge/TypeScript-Express-red)


### JuneBug

Junebug is Mugsy's React frontend. Written in TypeScript, with some help from [Tailwind](https://tailwindcss.com/), [DaisyUI](https://daisyui.com/), [TanStack Query](https://tanstack.com/query/latest), and [Jotai](https://jotai.org/). 


![](https://img.shields.io/badge/TypeScript-React-cyan)