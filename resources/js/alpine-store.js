document.addEventListener("alpine:init", () => {
    Alpine.store("app", {
        showLoginModal: false,
        user: JSON.parse(localStorage.getItem("user")) || null,
        token: localStorage.getItem("token") || null,

        init() {
            console.log("Alpine store initialized");
            if (this.token) {
                this.getUserProfile();
            }
        },

        async login(credentials) {
            try {
                console.log("Login attempt:", credentials);
                const response = await fetch("/api/auth/login", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify(credentials),
                });

                const data = await response.json();
                console.log("Login response:", data);

                if (data.success) {
                    this.token = data.data.token;
                    this.user = data.data.user;

                    localStorage.setItem("token", this.token);
                    localStorage.setItem("user", JSON.stringify(this.user));

                    showToast("Login berhasil!", "success");
                    this.showLoginModal = false;
                    window.location.reload();
                } else {
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0][0];
                        showToast(firstError, "error");
                    } else {
                        showToast(data.message || "Login gagal", "error");
                    }
                }
            } catch (error) {
                console.error("Login error:", error);
                showToast("Terjadi kesalahan jaringan", "error");
            }
        },

        async register(userData) {
            try {
                console.log("Register attempt:", userData);
                const response = await fetch("/api/auth/register", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify(userData),
                });

                const data = await response.json();
                console.log("Register response:", data);

                if (data.success) {
                    this.token = data.data.token;
                    this.user = data.data.user;

                    localStorage.setItem("token", this.token);
                    localStorage.setItem("user", JSON.stringify(this.user));

                    showToast("Pendaftaran berhasil!", "success");
                    this.showLoginModal = false;
                    window.location.reload();
                } else {
                    if (data.errors) {
                        const firstError = Object.values(data.errors)[0][0];
                        showToast(firstError, "error");
                    } else {
                        showToast(data.message || "Pendaftaran gagal", "error");
                    }
                }
            } catch (error) {
                console.error("Register error:", error);
                showToast("Terjadi kesalahan jaringan", "error");
            }
        },

        async getUserProfile() {
            try {
                const response = await fetch("/api/user/profile", {
                    method: "GET",
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                        Accept: "application/json",
                    },
                });

                const data = await response.json();

                if (data.success) {
                    this.user = data.data.user;
                    localStorage.setItem("user", JSON.stringify(this.user));
                }
            } catch (error) {
                console.error("Get profile error:", error);
                this.logout();
            }
        },

        logout() {
            if (this.token) {
                fetch("/api/user/logout", {
                    method: "POST",
                    headers: {
                        Authorization: `Bearer ${this.token}`,
                        Accept: "application/json",
                    },
                }).catch((error) => console.error("Logout API error:", error));
            }

            this.user = null;
            this.token = null;
            localStorage.removeItem("token");
            localStorage.removeItem("user");

            showToast("Logout berhasil", "success");
            window.location.reload();
        },

        isLoggedIn() {
            return this.user !== null && this.token !== null;
        },
    });
});
