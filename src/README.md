# 🗓️ Task Scheduler - Assignment Submission

This is my submission for the PHP-based Task Scheduler assignment.

---

## 🔧 Features Implemented

- ✅ Add, mark, and delete tasks
- ✅ Prevent duplicate tasks
- ✅ Store all data in `.txt` files using **valid JSON**
- ✅ Email subscription with verification system
- ✅ HTML email format with verification & unsubscribe links
- ✅ Unsubscribe system (one-click link)
- ✅ Hourly reminder system via Task Scheduler (Windows)
- ✅ Uses **pure PHP** (no external libraries or DB)

---

## 📂 Project Structure (Inside `src/`)

| File                     | Purpose                                 |
|--------------------------|-----------------------------------------|
| `index.php`              | Main interface                         |
| `functions.php`          | Core logic and all required functions  |
| `verify.php`             | Handles email verification             |
| `unsubscribe.php`        | Handles one-click unsubscription       |
| `README.md`              | Provides the nescessary information about this assignment |
| `cron.php`               | Sends reminder emails to subscribers   |
| `setup_cron.bat`         | Windows-only cron replacement via Task Scheduler |
| `tasks.txt`              | Stores tasks in JSON                   |
| `subscribers.txt`        | Stores verified subscribers in JSON    |
| `pending_subscriptions.txt` | Stores pending email verifications |

---

## 🧪 Testing Notes

### 📧 Email Testing Using [Mailpit](https://mailpit.axllent.org/)

- Mailpit installed locally (port 8025)
- PHP configured to send email via:
SMTP: localhost
Port: 1025

- All emails were verified to:
- Contain correct subject and HTML body
- Include verification and unsubscribe links

### 🕐 Reminder System (Windows)

Since CRON is unavailable on Windows, a Windows-equivalent script was provided.

> ✅ `setup_cron.bat` registers an hourly **Task Scheduler** job that runs `cron.php`.

---

## ⚠️ Notes

- All functionality is implemented only inside the `src/` folder.
- Function names and file structure strictly follow the instructions.
- No external libraries, frameworks, or databases were used.
- No changes were made to files outside `src/`.

---

## 🙋 About Me

**Name:** Souvik Dutta  
**GitHub:** [https://github.com/Souvik21937](https://github.com/Souvik21937)

---

Thank you for reviewing my submission!
