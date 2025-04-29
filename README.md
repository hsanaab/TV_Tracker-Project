# 📺 TV Tracker

Welcome to **TV Tracker**, a web application where users can log, rate, comment on, and track their favorite TV shows!

---

## 📚 Project Overview

TV Tracker allows users to:
- Add TV shows to a shared database
- Rate and comment on shows
- Track watch status ("Watching", "Watched", "To Watch")
- Search and view other users' reviews
- View your own and others' watchlists
- Manage personal accounts with username/email/password

---

## ✨ Features

- 🔒 Secure user login and signup (password hashing)
- ➕ Add TV shows if not already in the database
- 🎭 Attach multiple genres (up to 3 per show)
- ⭐ Rate shows from 1-5 and comment
- 🔎 Search reviews by Title, Genre, or Rating
- 📋 View your own watchlist or search by Username
- 🚫 Inline error messages (no blank screens)
- 🔥 Built-in SQL triggers and indexing for performance

---

## 🛠️ Technologies Used

- PHP (backend logic)
- MySQL (database)
- HTML5 / CSS3 (frontend)
- JavaScript (minor interactivity)
- Apache Server (via XAMPP for local development)

---

## 🗄️ Database Design

The system is normalized to 3NF and uses relational integrity through constraints, triggers, and indexing.

### Core Tables:
- **Users**: Stores usernames, emails, hashed passwords
- **TV_Shows**: Master list of shows
- **Genres**: List of possible genres
- **Show_Genres**: Many-to-many relationship between shows and genres
- **User_Watchlist**: User-specific watch statuses
- **User_Show_Data**: User-specific ratings and comments

> 📈 ERD (Entity-Relationship Diagram) included separately.

### Triggers:
- Enforce max 3 genres per show
- Auto-fill review dates
- Prevent duplicate TV shows
- Maintain data consistency

### Indexes:
- Title (TV_Shows)
- Genre_ID (Show_Genres)
- User_ID (User_Watchlist and User_Show_Data)
- Rating (User_Show_Data)

---

## ⚙️ Setup Instructions

1. Install XAMPP and start Apache and MySQL.
2. Import the provided SQL dump file into your MySQL server.
3. Place the project folder (`TV_Tracker/`) into your `/htdocs/` directory.
4. Access the app via `http://localhost/TV_Tracker/` in your browser.
5. Create a new account or log in to start!

---

## 🌟 Future Enhancements

- 🎨 Improve styling and mobile responsiveness
- 🧑‍💼 Add user profile pages
- 🔐 Password reset / recovery functionality
- 👍 Like/upvote system for reviews
- 🛠️ Admin panel for show/user management
- 📧 Email validation and verification
- 🔥 Autocomplete genres when adding shows

---

## 📣 Author

Built with ❤️ by [Your Name Here].

---

## 📌 Submission Checklist

✅ Database normalization and design (ERD included)  
✅ Full SQL schema with constraints, triggers, and indexes  
✅ Frontend and backend separation  
✅ Full testing and validation  
✅ Professional documentation (README + ERD)

---
