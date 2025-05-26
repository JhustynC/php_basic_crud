class UserAjax {
  constructor() {
    this.baseUrl = "controllers/userController.php";
  }

  async createUser(formData) {
    return this.sendRequest("POST", `${this.baseUrl}?action=create`, formData);
  }

  async updateUser(formData) {
    return this.sendRequest("POST", `${this.baseUrl}?action=update`, formData);
  }

  async deleteUser(id) {
    return this.sendRequest("GET", `${this.baseUrl}?action=delete&id=${id}`);
  }

  async getUser(id) {
    return this.sendRequest("GET", `${this.baseUrl}?action=get&id=${id}`);
  }

  async getAllUsers() {
    return this.sendRequest("GET", `${this.baseUrl}?action=list`);
  }

  async sendRequest(method, url, data = null) {
    const options = {
      method,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    };

    if (data && method === "POST") {
      options.body = data;
    }

    try {
      const response = await fetch(url, options);
      return await response.json();
    } catch (error) {
      return { success: false, message: "Error de conexión" };
    }
  }

  showMessage(success, message) {
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert ${success ? "alert-success" : "alert-danger"}`;
    alertDiv.textContent = message;
    document.body.prepend(alertDiv);

    setTimeout(() => alertDiv.remove(), 3000);
  }
}

const userAjax = new UserAjax();
