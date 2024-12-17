# 🌥️ TOTCLOUD: PaaS & SaaS Management Platform

**🚀 Built for:**  
*Advanced Databases (Base de Dades 2) – University of the Balearic Islands (UIB)*  
📚 **Course Year:** 2024/25  
🧑‍💻 **Authors:** Michele Vincenzo Gentile & Antonio Contestí Coll  

---

## 📖 Table of Contents

1. [Introduction](#-introduction)  
2. [🎯 Features](#-features)  
3. [🛠️ Technologies Used](#-technologies-used)  
4. [🛢️ Database Design](#-database-design)  
5. [🧩 Functionality Overview](#-functionality-overview)  
6. [🎥 Demo](#-demo)  
7. [🚀 How to Run](#-how-to-run)  
8. [👥 Authors](#-authors)  

---

## ✨ Introduction

**TOTCLOUD** is a full-featured platform developed as part of a university assignment for the *University of the Balearic Islands (UIB)*. This project provides robust management solutions for both **PaaS** (*Platform as a Service*) and **SaaS** (*Software as a Service*). 🌐  

The goal? Build a secure, scalable, and user-friendly app to manage infrastructure, users, and resources. 

---

## 🎯 Features

### 🌟 Core Functionalities  
- **🔒 Secure User Management:** Login system with 2FA (SMS & email validation) and password history tracking.  
- **👥 Role Management:** Assign and manage user roles with role-based permissions.  
- **⚙️ PaaS Configuration:** Create and customize dedicated servers (memory, processors, storage, etc.).  
- **☁️ SaaS Configuration:** Manage hosting services, including SSL certificates, databases, and CDN integration.  
- **🔄 Automated Backups:** Daily incremental backups using database triggers.  
- **📋 Audit Logs:** Monitor user activity and key events for security.  

### 💡 Why It Rocks  
- **Modular Design:** Easily add new features or modules.  
- **Admin-Friendly:** Manage resources and requests with an intuitive interface.  
- **Scalable:** Future-proof database design for additional functionalities.  

---

## 🛠️ Technologies Used

| **Tech**          | **Description**                          |
|-------------------|------------------------------------------|
| **PHP**          | Backend logic for the application.       |
| **MySQL**        | Database management.                     |
| **HTML/CSS/JS**  | Frontend for user interaction.           |
| **XAMPP**        | Local development environment.           |
| **GitHub**       | Version control and collaboration.       |
| **StarUML**      | Data modeling and architecture design.   |
| **Mermaid**      | Diagrams for workflows and relationships.|

---

## 🛢️ Database Design

The heart of the application 💙:  

![Diagram]()

---

## 🧩 Functionality Overview

### 🔑 **Authentication & Role Management**  
- Register securely with SMS & email validation.  
- Assign roles with permissions.  
- Enforce strong passwords with historical validation.  

### ⚙️ **PaaS Configuration**  
- Customize dedicated servers: Memory, storage, CPU, OS, and more.  
- Admin approval for server configurations.  

### ☁️ **SaaS Configuration**  
- Manage hosting resources: SSL, databases, CDN integration...
- Admin control over hosting configurations.  

### 🗂️ **Automated Backups & Logging**  
- **Incremental Backups:** Triggered after database changes.  
- **Event Logs:** Keep track of critical actions and changes for auditing.  

---

## 🎥 Demo

🎉 **Coming Soon!**

| **Login Page**    | **Admin Dashboard**   | **Server Config**     |
|--------------------|-----------------------|-----------------------|
| ![Login]() | ![Dashboard]() | ![Config]() |

---

## 🚀 How to Run

### 🖥️ Requirements:
- **XAMPP** (Apache + MySQL)  
- **Git**  
- **PHP 7.x+**  

### 🛠️ Steps to Launch:  

1. **Clone this repository**:  
   ```bash
   git clone https://github.com/mivige/TotCloudApp.git
   cd TotCloudApp
   ```

2. **Set up the database**:  
   - Import the SQL files in `database/migrations` into your MySQL database.  

3. **Configure DB settings**:  
   - Update your database credentials in `totcloud-application/api/config/database_app.php`.  

4. **Start the server**:  
   - Run Apache and MySQL using XAMPP.  
   - Place the project in `htdocs` (or equivalent directory).  

5. **Access the application**:  
   - Open your browser and visit:  
     ```  
     http://localhost/TotCloudApp
     ```  

6. 🎉 **Enjoy your new platform!**

---

## 👥 Authors

Developed with 💻 and ☕ by:  

- **Michele Vincenzo Gentile**  
- **Antonio Contestí Coll**  

🏫 *University of the Balearic Islands (UIB)*  

---

🚀 Feel free to star ⭐ this repository and fork it for further improvements! Let us know if you use TOTCLOUD—we’d love to hear from you. 🥳  
