# SIS — Complete System Task & Capability Reference

**Product:** SIS (School Management System)  
**Type:** Multi-tenant SaaS school management platform  
**Stack:** PHP (custom MVC), MySQL (`school_system`), Apache (XAMPP), vanilla HTML/CSS/JS  
**Code root:** `school-management-system/`  
**Web root:** `public/`

This document describes **every task and capability** the codebase currently implements. Nothing listed here is aspirational marketing copy — each item maps to real routes, controllers, UI, or schema.

---

## Table of Contents

1. [What the System Is](#1-what-the-system-is)
2. [How Requests Are Routed](#2-how-requests-are-routed)
3. [Platform (Main Domain) Tasks](#3-platform-main-domain-tasks)
4. [School Tenant Site Tasks](#4-school-tenant-site-tasks)
5. [Authentication & Session Tasks](#5-authentication--session-tasks)
6. [SaaS Plans & School Provisioning](#6-saas-plans--school-provisioning)
7. [Director SaaS Console Tasks](#7-director-saas-console-tasks)
8. [School Director Portal Tasks](#8-school-director-portal-tasks)
9. [Teacher Portal Tasks](#9-teacher-portal-tasks)
10. [Student Portal Tasks](#10-student-portal-tasks)
11. [Parent Portal Tasks](#11-parent-portal-tasks)
12. [Communications (Messenger)](#12-communications-messenger)
13. [Academic Structure Tasks](#13-academic-structure-tasks)
14. [Assessment & Grading Tasks](#14-assessment--grading-tasks)
15. [Registration & ID Generation](#15-registration--id-generation)
16. [Theming, Templates & Page Builder](#16-theming-templates--page-builder)
17. [Document Template Library](#17-document-template-library)
18. [Public Marketing & Legal Pages](#18-public-marketing--legal-pages)
19. [Complete API Endpoint Inventory](#19-complete-api-endpoint-inventory)
20. [Database Tables & What They Store](#20-database-tables--what-they-store)
21. [Services, Models & Supporting Tools](#21-services-models--supporting-tools)
22. [Migration, Seeding & Demo Data](#22-migration-seeding--demo-data)
23. [Frontend Scripts & Behaviors](#23-frontend-scripts--behaviors)
24. [Verification & Local Setup Tasks](#24-verification--local-setup-tasks)
25. [Explicitly Not Implemented](#25-explicitly-not-implemented)
26. [File Map](#26-file-map)

---

## 1. What the System Is

SIS is a **single-codebase, multi-tenant** school platform:

- One Apache document root serves all tenants.
- Each school gets a **subdomain** (e.g. `vibrant.sis.localhost`).
- The main domain (`sis.localhost`) is the **SaaS marketing + director console**.
- Tenant data is isolated by `school_id` on almost every academic query.
- Roles: **director**, **teacher**, **student**, **parent** (schema also defines `admin` on `staff_users`, but no admin login/UI path is wired).

---

## 2. How Requests Are Routed

**Entry:** `public/index.php`  
**API:** paths starting with `/api/` → `public/api_routes.php`  
**Rewrite:** `public/.htaccess` funnels clean URLs to `index.php`

### Routing decision tree

| Condition | Behavior |
|-----------|----------|
| URI starts with `/api/` | JSON API (CORS + Bearer decode + role gates) |
| Host has subdomain (`{sub}.sis.localhost`) and school exists | Load school template or custom HTML override |
| Host has subdomain but school missing | `public/404-school.php` |
| Host is main domain + known static path | Auth pages, marketing/legal pages, director `dashboard.html` |
| Host is main domain `/` | `public/landing.php` |
| Unknown main-domain path | 404 via `404-school.php` |
| `?preview_subdomain=` on main domain | Page-builder iframe preview of a tenant site |

### School tenant page mapping

| URI | Template file |
|-----|----------------|
| `/` | `templates/{template}/index.php` |
| `/login.php` | `templates/{template}/login.php` |
| `/register.php` | `templates/{template}/register.php` |
| `/dashboard.php` | `templates/{template}/dashboard.php` |

If `school_site_content.custom_pages` JSON contains a key matching the request path, that stored HTML is served instead of the PHP template.

**Runtime note:** template name `vibrant` is remapped to `aurora` when loading PHP templates.

---

## 3. Platform (Main Domain) Tasks

On `sis.localhost` the system can:

1. Show the **marketing landing page** (`landing.php`) — product pitch, plan selection, CTAs to signup/login.
2. Run a **mock checkout** flow in `app.js` (`mockCheckout` / `processCheckout`) that stores an intended plan and redirects to signup (no real payment).
3. Open **director login** (`/auth/login`) and **director signup** (`/auth/signup`).
4. Open the **director SaaS dashboard** (`/dashboard` → `dashboard.html`).
5. Serve informational pages (security, help, docs, templates, API reference, privacy, terms, DPA).
6. Serve the **document templates** gallery (`/document-templates`).
7. Resolve APIs under `/api/*` without a school subdomain (school-scoped APIs need a subdomain or explicit school context where coded).

---

## 4. School Tenant Site Tasks

On `{subdomain}.sis.localhost` the system can:

1. Resolve the school from `schools.subdomain`.
2. Load branding from `school_site_content` (template, theme CSS path, typography, hero, logo, about, primary color, custom pages).
3. Inject CSS variables / theme styles into the school public pages.
4. Render the public school landing page for visitors.
5. Provide school **login** and **register** pages for roles.
6. Provide the unified **role dashboard** (`dashboard.php`) for director / teacher / student / parent after login.
7. Serve custom HTML page overrides saved by the page builder.
8. Show a friendly 404 when the subdomain does not match any school.

**Structural templates available:** `academic`, `aurora`, `blank`, `minimalist`, `vibrant` (PHP load remapped to aurora).

---

## 5. Authentication & Session Tasks

**Controller:** `src/Controllers/AuthController.php`  
**Platform UI:** `public/auth/login.php`, `signup.php`, `auth.js`  
**School UI:** `templates/*/login.php`, `register.php`

### Tasks performed

| Task | Details |
|------|---------|
| Register director | Creates `staff_users` row with `role='director'`, hashed password, optional `plan_id` |
| Login director (platform) | Username + password; returns Base64 JSON “Bearer” token |
| Login director (tenant) | Same credentials; must be the school’s `director_id` |
| Login teacher | Email **or** `teacher_id_code`; must belong to current school; status `active` |
| Login student | Email **or** `student_id`; must belong to current school; status `active` |
| Login parent | Email; must have ≥1 linked child in the current school |
| Issue auth token | `base64(json:{user_id, role, school_id, name})` stored client-side (`localStorage`) |
| Enforce role on APIs | `api_routes.php` checks Bearer payload before controller calls |
| Logout | Client clears token / redirects (dashboard JS) |

Passwords are stored with `password_hash` / verified with `password_verify`.

---

## 6. SaaS Plans & School Provisioning

### Plans (seeded in `sql/schema.sql`)

| Plan | Price | Max students | Max teachers | Max schools |
|------|-------|--------------|--------------|-------------|
| Free | 0 | 200 | 20 | 1 |
| Starter | 200 | 500 | 200 | 2 |
| Growth | 399 | 2000 | 500 | 5 |
| Scale | 1000 | 50000 | 1000 | 5 |

### Tasks

| Task | Who / where |
|------|-------------|
| List plans | `GET /api/schools/plans` → `SchoolController::getPlans` |
| Assign plan on director signup | `plan_id` from form / `localStorage.intendedPlan` |
| Create school | Director: name, subdomain, template, theme, description |
| Enforce max schools | Create blocked when director’s school count ≥ plan `max_schools` |
| Auto-generate school code | First 3 letters of name (+ numeric suffix if collision) |
| Link director to school | Updates `staff_users.school_id`; sets `schools.director_id` |
| Create default site content | Inserts `school_site_content` with hero defaults |
| List director’s schools | `GET /api/schools/list` with plan summary |

---

## 7. Director SaaS Console Tasks

**UI:** `public/dashboard.html` + `public/assets/js/app.js` + `page-editor.js`

The platform director dashboard can:

1. Gate access (require director token; redirect to login if missing).
2. List all schools owned by the logged-in director.
3. Show current plan limits / usage context.
4. Open a **Create School** modal (name, subdomain, structural template, color theme, description).
5. Open **Manage School** actions per school:
   - **Mass Registration** — upload CSV for students or teachers; download generated credentials CSV.
   - **Teacher Assignments** — UI shell for assignment management (school portal is the full workflow).
   - **Settings** — save theme path / typography / related site settings via `POST /api/schools/save-settings`.
   - **Edit Pages** — visual page builder (iframe preview with `preview_subdomain`).
6. Visit the live school site via subdomain link.
7. Log out.

---

## 8. School Director Portal Tasks

**UI:** role portal inside `templates/*/dashboard.php` (richest implementation: `aurora`)  
**Controller:** `DirectorPortalController.php`

### Overview

- View school stats: student count, teacher count, section count, and related overview metrics (`GET /api/director/stats`).

### Register users

- Create a **single student** (name, email, password) → auto ID `{CODE}{NNNN}/{YY}`.
- Create a **single teacher** (name, email, password, specialization) → auto ID `{CODE}T{NNNN}/{YY}`.
- **Mass-register** students or teachers via CSV upload (`POST /api/users/mass-register`).
- Download a credentials CSV of successfully created accounts.

### Sections (classes)

- List sections with grade level and stream.
- **Create section** (grade level, section name, stream: general / natural_science / social_science) — creates/finds matching `grades` row.
- **Update section** (name, grade level, stream).
- **Delete section** — cascades related teaching assignments and clears student section links as coded.

### Faculty schedule (teaching assignments)

- Load assignment data: teachers, subjects, sections, existing assignments (`GET /api/director/assignment-data`).
- **Assign teacher** to subject + section for the active academic year.
- **Remove** a teaching assignment.
- **Assign homeroom teacher** to a section.

### Curriculum (subjects)

- List subjects.
- Add subject (name + grade level).
- Edit subject.
- Delete subject (when allowed by controller rules).

### Student roster / sectioning

- View unassigned and assigned students.
- Manually assign a student to a section.
- Run **random sectioning** for a grade (distributes unassigned students across that grade’s sections).

### Parents

- List parents linked to students in the school.
- Create a parent (name, email, phone, relation) and **link** to a student; default password `password123` when created via director flow.

### Academic years & assessment types

- List academic years.
- Create academic year (name; optionally configure accompanying terms).
- Set an academic year as **active** (deactivates others).
- Delete an academic year if it is not the active one (with related cleanup as coded).
- List / create / delete **assessment types** (name + weight); delete blocked if type is in use.

### System config

- Configure **2-term (semesters)** or **3-term (trimesters)** system for the active year.
- Set the **active term**.
- Toggle **final assessment mode** (`schools.is_final_assessment_active`) so homeroom teachers can submit year-end pass/fail evaluations.

---

## 9. Teacher Portal Tasks

**Controller:** `PortalController.php`

1. View **My Classes** — teaching assignments (subject + section) for the teacher.
2. Open a class **roster** (students in the section).
3. List **assessments** for an assignment (filtered to active term / school types).
4. **Create assessment** (title, type, max score, date) tied to teaching assignment + active term.
5. **Update assessment** metadata.
6. **Delete assessment** (and related grade entries).
7. Enter grades for an entire assessment (bulk submit).
8. Open a **per-student grade editor** for all assessments in an assignment.
9. Submit grades for one student across assessments (upsert: delete old row + insert).
10. If assigned as **homeroom teacher**:
    - Load homeroom roster with computed averages / ranking context.
    - Submit **final evaluations** (average, rank, pass/fail/pending) when final-assessment mode is on.
11. Use **Messenger** to message parents of students in assigned sections (and view thread).

---

## 10. Student Portal Tasks

1. View **My Courses** — subjects for the student’s section with teacher info.
2. Open a course to see **individual assessment scores**, max scores, types, and weighted context.
3. View **Year-End Report / final evaluation** (average, class rank, pass/fail status) when published.
4. Use **Messenger** to contact teachers of their section.
5. Log out.

---

## 11. Parent Portal Tasks

1. View **My Children** linked via `parent_student` (scoped to current school).
2. Open a child’s **academic progress**:
   - Child’s courses.
   - Child’s grades per subject.
   - Child’s final evaluation.
3. Use **Teacher Messenger** to contact teachers of children’s sections.
4. Log out.

---

## 12. Communications (Messenger)

**Controller:** `CommunicationController.php`  
**Table:** `communications`

| Task | Details |
|------|---------|
| List messages | All messages where user is sender or receiver within school |
| Enrich names | Joins teachers / staff_users / parents for display names |
| Build contact lists | Role-specific (see below) |
| Send message | Stores school_id, sender role/id, receiver role/id, message text |

### Contact list rules

| Role | Can message |
|------|-------------|
| Teacher | Parents of students in sections they teach |
| Parent | Teachers of their children’s sections |
| Student | Teachers of their section (contacts listed; send uses same API) |
| Director | All teachers + all parents linked to school students |

Students are not a `sender_role` / `receiver_role` ENUM value in schema (`teacher`, `parent`, `director` only) — student messaging behavior depends on how the UI calls the API; schema supports teacher/parent/director threads primarily.

---

## 13. Academic Structure Tasks

| Entity | Tasks |
|--------|-------|
| Academic years | Create, list, activate, delete (non-active) |
| Terms | Created with year / reconfigured as 2 or 3 terms; set active term |
| Grades (levels) | Created implicitly when creating sections (level + stream) |
| Sections | Full CRUD; optional homeroom teacher |
| Subjects | Full CRUD per school + grade level |
| Teaching assignments | Assign / remove teacher↔subject↔section↔year |
| Student ↔ section | Manual assign, random sectioning, cleared on section delete |

Streams supported: `general`, `natural_science`, `social_science`.

---

## 14. Assessment & Grading Tasks

### Assessment types (school-level)

- Define named types with decimal **weights** (e.g. quiz 0.10, mid 0.30, final 0.40).
- Used when teachers create assessments.

### Assessments (per teaching assignment + term)

- Create / update / delete.
- Fields: type, title, max_score, assessment_date.

### Grade entries

- Bulk submit scores for a section roster on one assessment.
- Per-student multi-assessment submit.
- Stored in `grades_entries` (assessment_id, student_id, score).

### Computed analytics (`GradingService`)

| Function | Task |
|----------|------|
| `getStudentSubjectAverage` | Weighted average for one subject in a term: `(score/max)*weight*100` |
| `getStudentOverallAverage` | Mean of subject averages across enrolled subjects |
| `getSectionRankings` | Rank students in a section by overall average (desc) |
| `getSectionAverage` | Mean of student overall averages in a section |

### Final evaluations

- Homeroom teacher submits `student_final_evaluations` (average_score, class_rank, status pass/fail/pending).
- Students and parents can read the evaluation for the active year.
- Gated by `is_final_assessment_active` on the school.

### Unwired helpers

`AssessmentController` also defines `recordScore`, `getStudentReport`, `getSectionPerformance` — present in code but **not registered** in `api_routes.php`.

---

## 15. Registration & ID Generation

### Single create (`DirectorPortalController::createSingleUser`)

- Student or teacher with name, email, password.
- Uses school sequences for next number.

### CSV mass register (`RegistrationController::processCsv`)

| Capability | Detail |
|------------|--------|
| Accept file | Multipart upload; role = student\|teacher; requires `school_id` |
| Separators | Auto-detect `,` / `;` / tab |
| BOM handling | Strips UTF-8 BOM |
| Columns | Detects name/email headers; fallback columns 0 and 1 |
| Skip bad rows | Missing name/email, duplicate emails, etc. |
| Default password | Generated/assigned per row; returned in results for CSV download |
| Samples | `public/assets/samples/students_sample.csv`, `teachers_sample.csv`, `teacher.csv` |

### ID formats (`IDGeneratorService` + controller sequence helpers)

| Entity | Format |
|--------|--------|
| Student | `{SCHOOL_CODE}{NNNN}/{YY}` |
| Teacher | `{SCHOOL_CODE}T{NNNN}/{YY}` |

Sequences live in `school_sequences` (`next_student_no`, `next_teacher_no`).

### Parent create + link

- Creates parent account if needed and inserts `parent_student` relation (relation_type e.g. Father/Mother/Guardian).

### School self-register pages

- Tenant `register.php` templates allow role-based self-registration UI that posts into the same registration/auth flows (per template JS).

---

## 16. Theming, Templates & Page Builder

### Structural templates (`templates/`)

Each template folder provides: `index.php`, `login.php`, `register.php`, `dashboard.php` (+ optional `assets/css`).

| Template | Character (as used in product) |
|----------|--------------------------------|
| academic | Traditional / structured |
| blank | Modern bento-grid |
| minimalist | Clean / sparse |
| aurora | Primary modern portal (also used when vibrant is selected at runtime) |
| vibrant | Present as folder; PHP template name remapped to aurora |

### Color themes

`public/assets/css/themes/theme1.css` … `theme16.css` — selectable via `school_site_content.theme_path`.

### Site content fields the system stores / injects

- `template_name`, `theme_path`, `typography`
- `hero_title`, `hero_subtitle`, `meta_description`
- `primary_color`, `logo_url`, `about_text`
- `custom_pages` (JSON map of path → HTML)

### Page builder (`page-editor.js`)

Tasks:

1. Load school page in same-origin iframe using `preview_subdomain`.
2. Visually edit blocks / properties.
3. Undo / redo edits.
4. Save full HTML for a path via `POST /api/schools/save-page`.
5. Persist overrides so next public visit serves the custom HTML.

### Settings save (`SchoolController::saveSettings`)

Updates theme path, typography, and other allowed site-content fields for a subdomain.

---

## 17. Document Template Library

**Page:** `/document-templates`  
**API:** `TemplateController` (in-memory definitions, not DB)  
**JS:** `document-templates.js`

### Tasks

- List templates (optional category filter + search).
- Get one template by id.
- Duplicate a template (returns a copy with new id/name; client may keep copies in `localStorage`).
- Browse categories: Productivity, Business, Education, Personal, Development.

### Built-in templates (complete list)

**Productivity:** Daily Planner, Weekly Planner, Habit Tracker, Goal Tracker, Project Planner  

**Business:** Business Plan, Meeting Notes, SWOT Analysis, Invoice, Proposal  

**Education:** Study Planner, Lecture Notes, Research Notes, Assignment Tracker, Exam Preparation  

**Personal:** Journal, Travel Planner, Fitness Tracker, Budget Planner, Reading Tracker  

**Development:** Software Requirements Specification, Sprint Planner, Bug Tracker, API Documentation, System Design Document  

Each template includes structured sections (checklists, tables, prompts, text blocks, grids).

---

## 18. Public Marketing & Legal Pages

| Route | File | Purpose |
|-------|------|---------|
| `/` | `landing.php` | Marketing home |
| `/security` | `pages/security.php` | Security messaging |
| `/help-center` | `pages/help-center.php` | Help + FAQ (`help-center.js` accordion) |
| `/documentation` | `pages/documentation.php` | Product documentation |
| `/school-templates` | `pages/school-templates.php` | Gallery of school UI templates |
| `/document-templates` | `pages/document-templates.php` | Document template library UI |
| `/api-reference` | `pages/api-reference.php` | Public API docs mirror |
| `/privacy-policy` | `pages/privacy-policy.php` | Privacy policy |
| `/terms-of-service` | `pages/terms-of-service.php` | Terms |
| `/data-processing` | `pages/data-processing.php` | Data processing agreement style page |

Shared chrome: `public/includes/page-start.php`, `page-end.php`, `site-nav.php`, `site-footer.php`, `assets/css/pages.css`.

---

## 19. Complete API Endpoint Inventory

Base: `/api/{resource}/{action}`  
Auth: `Authorization: Bearer <base64-json>` unless noted.

### Auth

| Method | Path | Task |
|--------|------|------|
| POST | `/api/auth/register` | Register director |
| POST | `/api/auth/login` | Login any role (school-aware when subdomain present) |

### Student (role=student)

| Method | Path | Task |
|--------|------|------|
| GET | `/api/student/courses` | List student’s courses |
| GET | `/api/student/course-grades?subject_id=` | Grades for one subject |
| GET | `/api/student/final-evaluation` | Year-end evaluation |

### Parent (role=parent)

| Method | Path | Task |
|--------|------|------|
| GET | `/api/parent/children` | Linked children |
| GET | `/api/parent/child-courses?student_id=` | Child courses |
| GET | `/api/parent/child-grades?student_id=&subject_id=` | Child subject grades |
| GET | `/api/parent/child-evaluation?student_id=` | Child final evaluation |

### Teacher (role=teacher)

| Method | Path | Task |
|--------|------|------|
| GET | `/api/teacher/classes` | Teaching assignments |
| GET | `/api/teacher/class-students?section_id=` | Section roster |
| GET | `/api/teacher/assessments?assignment_id=` | Assessments for assignment |
| POST | `/api/teacher/create-assessment` | Create assessment |
| POST | `/api/teacher/update-assessment` | Update assessment |
| POST | `/api/teacher/delete-assessment` | Delete assessment |
| POST | `/api/teacher/submit-grades` | Bulk grade submit |
| GET | `/api/teacher/student-assignment-grades` | One student’s grades in assignment |
| POST | `/api/teacher/submit-student-grades` | Submit one student’s grades |
| GET | `/api/teacher/homeroom-roster?section_id=` | Homeroom roster + metrics |
| POST | `/api/teacher/submit-evaluations` | Submit final evaluations |

### Director (role=director)

| Method | Path | Task |
|--------|------|------|
| GET | `/api/director/stats` | School stats |
| GET | `/api/director/assignment-data` | Faculty schedule data |
| POST | `/api/director/assign-teacher` | Create teaching assignment |
| POST | `/api/director/remove-assignment` | Remove teaching assignment |
| POST | `/api/director/assign-homeroom` | Set homeroom teacher |
| POST | `/api/director/toggle-final-assessment` | Enable/disable final mode |
| GET | `/api/director/student-sectioning-data` | Roster/sectioning data |
| POST | `/api/director/assign-student-section` | Assign student to section |
| POST | `/api/director/random-sectioning` | Randomly place students |
| POST | `/api/director/create-section` | Create section |
| POST | `/api/director/update-section` | Update section |
| POST | `/api/director/delete-section` | Delete section |
| GET | `/api/director/parents-list` | List parents |
| POST | `/api/director/create-parent` | Create parent + link |
| POST | `/api/director/create-user` | Create student or teacher |
| GET/POST/PUT/DELETE | `/api/director/subjects` | Subject CRUD |
| GET/POST/PUT | `/api/director/terms` | List / configure term system / set active |
| GET/POST/PUT/DELETE | `/api/director/academic-years` | Academic year CRUD + activate |
| GET/POST/DELETE | `/api/director/assessment-types` | Assessment type manage |

### Communications (any logged-in role)

| Method | Path | Task |
|--------|------|------|
| GET | `/api/communications/list` | Messages + contacts |
| POST | `/api/communications/send` | Send message |

### Schools

| Method | Path | Task |
|--------|------|------|
| GET | `/api/schools/plans` | List SaaS plans |
| POST | `/api/schools/create` | Create school (director) |
| GET | `/api/schools/list` | List director schools |
| POST | `/api/schools/save-page` | Save custom page HTML |
| POST | `/api/schools/save-settings` | Save site settings |

### Users

| Method | Path | Task |
|--------|------|------|
| POST | `/api/users/mass-register` | CSV import (multipart; **no Bearer check in router**) |

### Templates

| Method | Path | Task |
|--------|------|------|
| GET | `/api/templates/list` | List/filter document templates |
| GET | `/api/templates/get?id=` | Get one template |
| POST | `/api/templates/duplicate` | Duplicate template definition |

---

## 20. Database Tables & What They Store

Schema file: `sql/schema.sql`

| Table | Purpose |
|-------|---------|
| `plans` | SaaS plan limits and pricing |
| `schools` | Tenant schools, subdomain, code, director, final-assessment flag |
| `staff_users` | Directors (and unused admin role), credentials, plan |
| `teachers` | Teacher profiles, codes, credentials, specialization |
| `students` | Student profiles, IDs, credentials, section, enrollment year |
| `parents` | Parent profiles and credentials |
| `parent_student` | Parent↔student links + relation_type |
| `academic_years` | Per-school years + active flag |
| `terms` | Terms/semesters under a year + active flag |
| `grades` | Grade levels + stream per school |
| `sections` | Named sections under a grade + optional homeroom |
| `subjects` | Curriculum subjects per school/grade |
| `teaching_assignments` | Teacher teaches subject in section for a year |
| `assessment_types` | Weighted assessment categories per school |
| `assessments` | Concrete graded events under an assignment/term |
| `grades_entries` | Student scores on assessments |
| `school_sequences` | Next student/teacher sequence numbers |
| `school_site_content` | Branding, theme, custom HTML pages |
| `communications` | In-app messages |
| `student_final_evaluations` | Homeroom year-end pass/fail records |

---

## 21. Services, Models & Supporting Tools

### Services

| Class | Tasks |
|-------|-------|
| `GradingService` | Subject/overall averages, section rankings & average |
| `IDGeneratorService` | Generate sequenced student/teacher IDs |

### Models (thin Active Record)

| Class | Tasks |
|-------|-------|
| `BaseModel` | `find`, `all`, `delete` |
| `Student` | `findBySchoolID`, `create`, `updateSection` |
| `Teacher` | `create` |
| `Staff` | `create` |

Most business logic lives in controllers, not models.

### Config

| File | Task |
|------|------|
| `src/Config/database.php` | PDO connection to MySQL |
| `src/Config/migrate.php` | Schema alters, ensure tables, seed demo school |
| `config/project.php` | Path reference helper (not required at runtime by front controller) |

### Apache

| File | Task |
|------|------|
| `apache-vhost.conf` | Sample vhost for `sis.localhost` + `*.sis.localhost` |

---

## 22. Migration, Seeding & Demo Data

Running `php src/Config/migrate.php` can:

1. Ensure required columns/tables exist (ALTER/CREATE as coded).
2. Seed demo school **Vibrant Academy**:
   - Subdomain: `vibrant`
   - School code: `VIB123`
   - Sample academic year, terms, grade/section, subjects, assignments
   - Sample assessments and grade entries
3. Seed demo users (password typically `password123`):
   - Director account for the demo school
   - `teacher@school.com`
   - `student@school.com`
   - `parent@school.com`

Fresh installs can also import `sql/schema.sql` (creates DB + tables + plans).

---

## 23. Frontend Scripts & Behaviors

| File | Tasks |
|------|-------|
| `public/assets/js/app.js` | Director dashboard auth gate, school list/create, mass CSV import + credential download, modals, mock checkout, sidebar |
| `public/auth/auth.js` | Platform login/register API calls |
| `public/assets/js/page-editor.js` | Visual page builder, undo/redo, save custom HTML |
| `public/assets/js/document-templates.js` | Browse/search/preview/duplicate document templates |
| `public/assets/js/help-center.js` | FAQ accordion |
| `templates/*/dashboard.php` (inline JS) | Full role portals: tabs, grading UIs, messenger, director admin panels, `apiRequest` helper |
| `templates/*/login.php` & `register.php` | School auth forms posting role + credentials |

---

## 24. Verification & Local Setup Tasks

Documented in `README.md` and exercised by:

| Task | How |
|------|-----|
| Map hosts | `sis.localhost`, school subdomains → `127.0.0.1` |
| Configure Apache vhost | DocumentRoot → `public/`, `ServerAlias *.sis.localhost` |
| Create DB | `school_system` + import schema / run migrate |
| Configure DB creds | `src/Config/database.php` |
| Verify pages/routes/templates | `public/tests/verify.php` |
| Manual QA flows | Platform signup, subdomain resolve, grading, messenger, page builder (README tester guide) |

---

## 25. Explicitly Not Implemented

Confirmed absent from schema/routes/controllers (even if marketing text mentions them):

- Fee collection, invoices, billing for school operations
- Attendance tracking
- Timetable / bell schedule engine
- Library, transport, hostel, inventory, payroll
- Real exam scheduling (beyond assessment scoring)
- Email / SMS sending or password reset by email
- Push notifications
- Platform **admin** role UI (ENUM exists only)
- True JWT/session cookies/CSRF middleware
- PDF / print report-card pipeline
- Real payment gateway (checkout is mocked)
- Soft-delete UIs for students/teachers
- Dedicated logo file upload endpoint (logo is a URL field)

---

## 26. File Map

```
school-management-system/
├── README.md
├── PROJECT_TASKS.md          ← this document
├── System-Overview.txt
├── apache-vhost.conf
├── sampleCSV.txt
├── config/project.php
├── sql/schema.sql
├── src/
│   ├── Config/
│   │   ├── database.php
│   │   └── migrate.php
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── SchoolController.php
│   │   ├── RegistrationController.php
│   │   ├── PortalController.php
│   │   ├── DirectorPortalController.php
│   │   ├── CommunicationController.php
│   │   ├── AssessmentController.php
│   │   └── TemplateController.php
│   ├── Models/
│   │   ├── BaseModel.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   └── Staff.php
│   └── Services/
│       ├── GradingService.php
│       └── IDGeneratorService.php
├── public/
│   ├── index.php
│   ├── api_routes.php
│   ├── landing.php
│   ├── dashboard.html
│   ├── 404-school.php
│   ├── .htaccess
│   ├── auth/
│   ├── pages/
│   ├── includes/
│   ├── tests/verify.php
│   └── assets/
│       ├── js/
│       ├── css/ (+ themes theme1–theme16)
│       └── samples/
└── templates/
    ├── academic/
    ├── aurora/
    ├── blank/
    ├── minimalist/
    └── vibrant/
```

---

## Role → Task Cheat Sheet

| Role | Can do |
|------|--------|
| **Visitor (platform)** | Browse landing, plans, docs, legal, document templates; sign up / log in as director |
| **Director (SaaS)** | Create/list schools within plan limits; mass-register; theme/settings; page builder |
| **Director (school portal)** | Stats, users, sections, subjects, assignments, sectioning, parents, years/terms, assessment types, final-mode toggle, messenger |
| **Teacher** | Classes, assessments CRUD, grade entry, homeroom evaluations, messenger |
| **Student** | Courses, grades, final report, messenger |
| **Parent** | Children progress (courses/grades/evaluation), messenger with teachers |

---

*Generated from the live codebase under `school-management-system/`. Update this file when adding or removing features.*
