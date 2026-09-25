/**
 * script.js
 * Login lab: GET + POST + Cookie + Validation
 * Работает полностью на клиенте — подходит для GitHub Pages.
 */

// "База" пользователей для демонстрации (в реальном проекте — на сервере, с хешами паролей)
const USERS = {
    admin: 'admin123',
    user1: 'password1',
};

// ---------- Утилиты: Cookie ----------
function setCookie(name, value, days) {
    let expires = '';
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = `${name}=${encodeURIComponent(value)}${expires}; path=/`;
}

function getCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
}

function deleteCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
}

// ---------- Утилиты: GET-параметры из URL ----------
function getQueryParam(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}

// ---------- Валидация ----------
function validateCredentials(username, password) {
    const errors = [];
    if (!username) {
        errors.push('Введите логин');
    } else if (!/^[a-zA-Z0-9_]{3,20}$/.test(username)) {
        errors.push('Логин: 3-20 символов (латиница, цифры, _)');
    }
    if (!password) {
        errors.push('Введите пароль');
    } else if (password.length < 6) {
        errors.push('Пароль должен быть не менее 6 символов');
    }
    return errors;
}

// ---------- Страница логина ----------
function initLoginPage() {
    const errorBox = document.getElementById('errorBox');
    const usernameInput = document.getElementById('username');
    const loggedInBox = document.getElementById('loggedInBox');
    const loginForm = document.getElementById('loginForm');

    // Демонстрация GET: читаем параметры ошибки/логина из URL после редиректа
    const error = getQueryParam('error');
    const prevUsername = getQueryParam('username');
    if (error && errorBox) {
        errorBox.textContent = error;
        errorBox.style.display = 'block';
    }
    if (prevUsername && usernameInput) {
        usernameInput.value = prevUsername;
    }

    // Если уже авторизован — показываем ссылку на кабинет вместо формы
    if (getCookie('logged_in') === '1') {
        if (loginForm) loginForm.style.display = 'none';
        if (loggedInBox) {
            loggedInBox.style.display = 'block';
            document.getElementById('loggedInName').textContent = getCookie('username');
        }
        return;
    }

    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmit);
    }
}

async function handleLoginSubmit(e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const remember = document.getElementById('remember').checked;
    const submitBtn = e.target.querySelector('button[type="submit"]');

    submitBtn.disabled = true;
    submitBtn.textContent = 'Проверка...';

    // ---- Демонстрация реального GET-запроса ----
    // (например, "проверка занятости логина" на внешнем echo-сервисе)
    try {
        await fetch(`https://httpbin.org/get?username=${encodeURIComponent(username)}`);
    } catch (err) {
        console.warn('GET demo request failed (не критично):', err);
    }

    // ---- Валидация ----
    const errors = validateCredentials(username, password);
    if (errors.length) {
        redirectWithError(errors.join('; '), username);
        return;
    }

    // ---- Демонстрация реального POST-запроса ----
    // (отправка учётных данных, как это делала бы форма на настоящий сервер)
    try {
        await fetch('https://httpbin.org/post', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password }),
        });
    } catch (err) {
        console.warn('POST demo request failed (не критично):', err);
    }

    // ---- Проверка учётных данных ----
    if (!USERS[username] || USERS[username] !== password) {
        redirectWithError('Неверный логин или пароль', username);
        return;
    }

    // ---- Успех: устанавливаем cookie ----
    const days = remember ? 7 : 1; // "запомнить" — 7 дней, иначе — короткоживущая cookie
    setCookie('username', username, days);
    setCookie('logged_in', '1', days);

    window.location.href = 'dashboard.html';
}

function redirectWithError(message, username) {
    const url = `index.html?error=${encodeURIComponent(message)}&username=${encodeURIComponent(username)}`;
    window.location.href = url;
}

// ---------- Страница личного кабинета ----------
function initDashboardPage() {
    if (getCookie('logged_in') !== '1') {
        window.location.href = `index.html?error=${encodeURIComponent('Сначала войдите в систему')}`;
        return;
    }
    const nameEl = document.getElementById('dashUsername');
    if (nameEl) nameEl.textContent = getCookie('username');

    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            deleteCookie('username');
            deleteCookie('logged_in');
            window.location.href = 'index.html';
        });
    }
}
