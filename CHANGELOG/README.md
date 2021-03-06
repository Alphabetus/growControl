# Changelog  

### [v0.10 | 05/feb/19] > Security update on post requests

- Add authenticity token engine to pair and verify each post request.
- Patch several files to support and require the new security token.

### [v0.9 | 02/feb/19] > Patch room log handling
- Enable room log listing and removal.
- Patch `room.js` to handle confirmation and `ajax_request` to delete log entry.
- Rename `grow_log_entries` as `grow_note_entries`.
- Add new `grow_log_entries` as the default file to deliver the log entries partial view.
- Patch `theme01` to handle log table.
- Patch `grow_view` to deliver the new requested displays.


### [v0.8 | 31/jan/19] > Small fixes

- Patch `room.js` to enhance input data validation.
- Patch `addGrow_view` and `editGrow_view` to style input forms.


### [v0.7 | 31/jan/19] > Fix general grows display

- Fix general grows view to display average optional grow statistics based on the given entries.

### [v0.6 | 31/jan/19] > Grow system completion

- Add several `ajax_requests` files to handle with grow specific notes without reloading the page.
- Patch `addGrow_engine` to better fit the needed requests and data handling.
- Severely patch `room.js` to accommodate all the require interactivity and ajax requests.
- Add partial `grow_log_entries` to accommodate all log entries and better organise `grow_view`.
- Add some strings to populate new areas.
- Patch `theme01` to implement the new styling.

### [v0.5 | 29/jan/19] > Grow system near completion & code cleanup

- Add `tracks_table` on DB to store all the tracking systems independently from the scope they are called from.
- Add ajax requests to allow real time statistics logging without additional page reloads.
- Introduce `edit_grow` functionalities and view.
- Add `jQuery-ui` library to implement some graphical effects.
- Create several partials to host the code launched on `grow_view` to simplify maintenance and readability.
- Patch `theme01` to face the new demands.

### [v0.4] > Individual grow view and controller & bug fixes

- Fix bug on `grows_engine` on empty grow grid scenario.
- Fix bug blocking correct menu usage under some resolutions.
- Change `grow volume` to `grow height` to simplify usability and patch all required engines and views.
- Patch `general_engine` to face the new demands.
- Introduce `room_engine` and view to control & display individual room information.
- Define default grow avatar.
- Patch `theme01` to format new views available.

### [v0.3] > Grow index system front and back & dynamic validation on registration

- Introduce ajax requests for dynamic data handling on registration form.
- Validate registration form according to syntax and username availability.
- Add `grow_engine`, `addGrow_engine` and frontend design for the respective views.
- Introduce new partial, `section_header`.
- Patch navigation bars to display current page title.
- Patch sidebar to improve user usability.
- Patch `theme01` to face new and future demands with `.panel-container-*` creation for dashboard panels handling.
- Add new string files.
- Patch `general_engine` to face the new demands.

### [v0.2] > User creation, authentication & permission engine. Frontend patch to face new views

- Fix `dbConfig_example.php`.
- Add `cronjobs` folder to organise automated tasks.
- Add automated task to logout inactive users on a timely manner.
- Path `index.php` & `general_engine.php` to enable the new functionalities.
- Add register javascript file to prepare for frontend dynamic validation during registration.
- Add new main navigation bar to replace the default one as soon as user logs in.
- Patch main theme to style the new areas & views.
- Add welcome & dashboard views.
- Add several string files to populate new areas.
- Add login & registration engine.
- Enable user registration without sensitive information.
- Encrypt significant data to keep user anonymity.
- Enable authenticated users' access to dashboard view.


### [v0.1] > Frontend for guests & basic backend setup  

- Apply responsive main theme for guest users.
- Add string retrieval from `txt` files to simplify content updates.
- Add landing, login & register views.
- Add user interaction to landing page enabling space for further necessary content without leaving the same page.
- Apply footer to main theme.
- Organise strings within language subfolders to enable further translation.


### [v0.0] > Initial commit with tree structure  

- Add `db` configuration example file.
- Create main project.
