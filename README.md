# Task Management App

Simple PHP task management application with authentication, task CRUD, status updates, sorting, pagination, and SweetAlert notifications.

## Features

- Register, login, and logout
- Add, edit, delete, and complete tasks
- Sort tasks by due date, description, and priority
- Today reminder alert
- SweetAlert validation and confirmation dialogs
- Pagination with 6 items per page

## Technology

- PHP version 8.4.16
- PostgreSQL
- HTML
- CSS
- JavaScript
- SweetAlert2

## Setup

1. Clone the repository.
2. Configure your database connection in `config/database.php`.
3. Import the database schema into PostgreSQL.
4. Run the project through your local PHP server.

## Project Structure

- `controllers/` - application controllers
- `models/` - database models
- `routes/` - routing entry points
- `views/` - page templates
- `css/` - stylesheets
- `js/` - client-side scripts
- `assets/` - screenshots used in this README

## Requirements Coverage

- User login and logout
- Task creation, editing, completion, and deletion
- Sorting by due date, description, and priority
- Today due-date reminder
- Adaptive UI for desktop, tablet, and mobile
- SweetAlert validation and confirmation dialogs
- English-only labels, messages, and notifications

## Application Flow

### 1. Register

The user creates a new account from the register page.

![Figure 1. Register page](assets/register-page.png)

If any required field is empty, a SweetAlert error appears.

![Figure 2. Register validation error](assets/alert-input_required-inRegisterPage.png)

### 2. Login

The user signs in using the login page.

![Figure 3. Login page](assets/login-page.png)

If the email field is empty, a SweetAlert error appears.

![Figure 4. Login validation error](assets/alert-email_required_inLoginPage.png)

If the credentials are correct, the user sees a success alert after login.

![Figure 5. Login success](assets/alert-success-login.png)

### 3. Dashboard

After login, the dashboard shows the task list and available actions.

![Figure 6. Dashboard page](assets/dashboard-page.png)

The dashboard supports task filtering:

- My Tasks

![Figure 7. My Tasks filter](assets/filter-myTasks.png)

- Completed Tasks

![Figure 8. Completed Tasks filter](assets/filter-completedTasks.png)

The table supports sorting by due date, description, and priority.

![Figure 9. Sort in table](assets/fitur-short-in-th-table.png)

### 4. Add Task

The user can add a new task from the add task page.

![Figure 10. Add task page](assets/page-add-task.png)

If required fields are empty, a SweetAlert error appears.

![Figure 11. Add task validation error](assets/alert-input-required-in-addTaskPage.png)

### 5. Edit Task

The user can edit an existing task from the edit page.

![Figure 12. Edit task page](assets/edit-page.png)

If required fields are empty, a SweetAlert error appears.

![Figure 13. Edit task validation error](assets/alert-input-requirede-in-editPage.png)

When the task status is updated, a success alert appears.

![Figure 14. Task status updated](assets/notif-task-status-updated.png)

### 6. Delete Task

Before deletion, the app shows a confirmation dialog.

![Figure 15. Delete confirmation](assets/alert_ask-delete.png)

After deletion, a success alert appears.

![Figure 16. Delete success](assets/alert-success-delete.png)

### 7. Logout

When the user clicks logout, the session is cleared and the app returns to the login page.

![Figure 17. Logout success](assets/alert-success-logout.png)

## Notes

- All UI text and notifications are in English.
- The screenshots above are available in the `assets` folder and arranged in flow order from register to logout.
- This README is organized to match the application flow from registration through logout for easier review.
