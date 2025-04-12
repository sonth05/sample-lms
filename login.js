        // JavaScript for header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.remove('transparent');
                header.classList.add('scrolled');
            } else {
                header.classList.add('transparent');
                header.classList.remove('scrolled');
            }
        });

        // JavaScript for modal
        const loginBtn = document.getElementById('loginBtn');
        const modalOverlay = document.getElementById('modalOverlay');
        const loginModal = document.getElementById('loginModal');
        const closeModal = document.getElementById('closeModal');

        loginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modalOverlay.classList.add('show');
            loginModal.classList.add('show');
        });

        closeModal.addEventListener('click', function() {
            modalOverlay.classList.remove('show');
            loginModal.classList.remove('show');
        });

        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove('show');
                loginModal.classList.remove('show');
            }
        });

document.addEventListener("DOMContentLoaded", function () {
    const header = document.querySelector(".header");

    // Khi tải trang, thêm lớp "transparent"
    header.classList.add("transparent");

    // Lắng nghe sự kiện cuộn
    window.addEventListener("scroll", function () {
        if (window.scrollY > 5) { // Giảm giá trị xuống 5
            header.classList.remove("transparent");
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
            header.classList.add("transparent");
        }
    });
});


require("dotenv").config(); // Load biến môi trường

const express = require("express");
const connectDB = require("./db");

const app = express();
connectDB();


const mongoose = require('mongoose');

const userSchema = new mongoose.Schema({
  username: { type: String, required: true },
  email:    { type: String, required: true, unique: true },
  password: { type: String, required: true },
  role:     { type: String, default: 'student' }
});

module.exports = mongoose.model('User', userSchema);

// routes/auth.js
const express = require('express');
const bcrypt = require('bcryptjs');
const User = require('../models/User');

const router = express.Router();

router.post('/register', async (req, res) => {
  const { username, email, password } = req.body;

  try {
    const userExists = await User.findOne({ email });
    if (userExists) return res.status(400).json({ msg: 'Email đã tồn tại' });

    const hashedPassword = await bcrypt.hash(password, 10);
    const user = new User({ username, email, password: hashedPassword });

    await user.save();
    res.status(201).json({ msg: 'Đăng ký thành công' });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

module.exports = router;

router.post('/login', async (req, res) => {
    const { email, password } = req.body;
  
    try {
      const user = await User.findOne({ email });
      if (!user) return res.status(400).json({ msg: 'Tài khoản không tồn tại' });
  
      const isMatch = await bcrypt.compare(password, user.password);
      if (!isMatch) return res.status(400).json({ msg: 'Sai mật khẩu' });
  
      res.json({ msg: 'Đăng nhập thành công', user });
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
  });
  

