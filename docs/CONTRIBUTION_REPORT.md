# Contribution Report

**Course:** BUC3131 Advanced Web Application Development, Coursework Two (2026/2027)
**Project:** Student Management System (Laravel 12)
**Repository:** https://github.com/DERRO12/SCHOOL-MGT-SYSTEM-

## Group members

> Fill in every member of the group (maximum 5).

| # | Name | Registration number | GitHub username |
| --- | --- | --- | --- |
| 1 | | | DERRO12 |
| 2 | | | |
| 3 | | | |
| 4 | | | |
| 5 | | | |

## Branching strategy

- `main`: stable, tested code only
- `develop`: integration branch; every feature branch is merged here through a pull request
- `feature/*`: one branch per feature, created from `develop`

The final version is merged from `develop` into `main` after the integrated application has been tested
(`php artisan migrate:fresh --seed` and `php artisan test`).

## Tasks, branches and pull requests

> Update the "Member" and "PR" columns with who did each task and the pull request number.

| Task | Coursework question | Branch | Member | PR |
| --- | --- | --- | --- | --- |
| Project setup, permission package, Blade layout | Q5a | `feature/project-setup` | | # |
| Migrations, models and Eloquent relationships | Q1 | `feature/relationships` | | # |
| Seeders, roles and permissions | Q2, Q3b, Q3c | `feature/seeders-roles` | | # |
| Authentication, middleware and policies | Q3a, Q3d, Q3e | `feature/authentication` | | # |
| Student CRUD and validation | Q4a, Q5b–d | `feature/student-management` | | # |
| Course CRUD and lecturer assignment | Q4b, Q5b–d | `feature/course-management` | | # |
| Course registration and role dashboards | Q4c–e | `feature/course-registration` | | # |
| README, screenshots and this report | Q6 | `feature/documentation` | | # |

## Contributions

> Summarise what each member did (commits, reviews, testing). The commit history on GitHub can be
> checked with `git shortlog -sn --all`.

| Member | Contribution summary | Commits | PRs reviewed |
| --- | --- | --- | --- |
| | | | |
| | | | |

## Testing before merging to `main`

- [ ] `composer install` on a clean clone
- [ ] `php artisan migrate:fresh --seed` succeeds on MySQL
- [ ] `php artisan test` passes
- [ ] Logged in as admin, lecturer and student and checked each role's pages
