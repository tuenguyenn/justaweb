<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sidebar Menu HTML and CSS | CodingNepal</title>
  <!-- Linking Google Font Link For Icons -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <aside class="sidebar">
    <div class="sidebar-header">
      <img src="images/logo.png" alt="logo" />
      <h2>CodingLab</h2>
    </div>
    <ul class="sidebar-links">
      <h4>
        <span>Main Menu</span>
        <div class="menu-separator"></div>
      </h4>
      <li>
        <a href="#">
          <span class="material-symbols-outlined"> dashboard </span>Dashboard</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> overview </span>Overview</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> monitoring </span>Analytic</a>
      </li>
      <h4>
        <span>General</span>
        <div class="menu-separator"></div>
      </h4>
      <li>
        <a href="#"><span class="material-symbols-outlined"> folder </span>Projects</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> groups </span>Groups</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> move_up </span>Transfer</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> flag </span>All Reports</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined">
            notifications_active </span>Notifications</a>
      </li>
      <h4>
        <span>Account</span>
        <div class="menu-separator"></div>
      </h4>
      <li>
        <a href="#"><span class="material-symbols-outlined"> account_circle </span>Profile</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> settings </span>Settings</a>
      </li>
      <li>
        <a href="#"><span class="material-symbols-outlined"> logout </span>Logout</a>
      </li>
    </ul>
    <div class="user-account">
      <div class="user-profile">
        <img src="images/profile-img.jpg" alt="Profile Image" />
        <div class="user-detail">
          <h3>Eva Murphy</h3>
          <span>Web Developer</span>
        </div>
      </div>
    </div>
  </aside>
</body>
</html>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
body {
  min-height: 100vh;
  background: #F0F4FF;
}
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 85px;
  display: flex;
  overflow-x: hidden;
  flex-direction: column;
  background: #161a2d;
  padding: 25px 20px;
  transition: all 0.4s ease;
}
.sidebar:hover {
  width: 260px;
}
.sidebar .sidebar-header {
  display: flex;
  align-items: center;
}
.sidebar .sidebar-header img {
  width: 42px;
  border-radius: 50%;
}
.sidebar .sidebar-header h2 {
  color: #fff;
  font-size: 1.25rem;
  font-weight: 600;
  white-space: nowrap;
  margin-left: 23px;
}
.sidebar-links h4 {
  color: #fff;
  font-weight: 500;
  white-space: nowrap;
  margin: 10px 0;
  position: relative;
}
.sidebar-links h4 span {
  opacity: 0;
}
.sidebar:hover .sidebar-links h4 span {
  opacity: 1;
}
.sidebar-links .menu-separator {
  position: absolute;
  left: 0;
  top: 50%;
  width: 100%;
  height: 1px;
  transform: scaleX(1);
  transform: translateY(-50%);
  background: #4f52ba;
  transform-origin: right;
  transition-delay: 0.2s;
}
.sidebar:hover .sidebar-links .menu-separator {
  transition-delay: 0s;
  transform: scaleX(0);
}
.sidebar-links {
  list-style: none;
  margin-top: 20px;
  height: 80%;
  overflow-y: auto;
  scrollbar-width: none;
}
.sidebar-links::-webkit-scrollbar {
  display: none;
}
.sidebar-links li a {
  display: flex;
  align-items: center;
  gap: 0 20px;
  color: #fff;
  font-weight: 500;
  white-space: nowrap;
  padding: 15px 10px;
  text-decoration: none;
  transition: 0.2s ease;
}
.sidebar-links li a:hover {
  color: #161a2d;
  background: #fff;
  border-radius: 4px;
}
.user-account {
  margin-top: auto;
  padding: 12px 10px;
  margin-left: -10px;
}
.user-profile {
  display: flex;
  align-items: center;
  color: #161a2d;
}
.user-profile img {
  width: 42px;
  border-radius: 50%;
  border: 2px solid #fff;
}
.user-profile h3 {
  font-size: 1rem;
  font-weight: 600;
}
.user-profile span {
  font-size: 0.775rem;
  font-weight: 600;
}
.user-detail {
  margin-left: 23px;
  white-space: nowrap;
}
.sidebar:hover .user-account {
  background: #fff;
  border-radius: 4px;
}
</style>