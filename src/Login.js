import React, { useState } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';

const Login = () => {
  const [loginData, setLoginData] = useState({
    email: '',
    password: '',
  });

  const [errors, setErrors] = useState({});
  
  const handleChange = (e) => {
    setLoginData({
      ...loginData,
      [e.target.name]: e.target.value,
    });
  };

  const validateForm = () => {
    let isValid = true;
    const newErrors = {};

    // Basic email validation
    if (!loginData.email || !/^\S+@\S+\.\S+$/.test(loginData.email)) {
      newErrors.email = 'Invalid email address';
      isValid = false;
    }

    // Basic password validation
    if (!loginData.password || loginData.password.length < 6) {
      newErrors.password = 'Password must be at least 6 characters';
      isValid = false;
    }

    setErrors(newErrors);
    return isValid;
  };

  const handleLogin = async () => {
    if (!validateForm()) {
        // Form validation failed
        return;
      }

    try {
      const response = await fetch('http://localhost:80/registration_api/login.php', {
        method: 'POST',
        body: JSON.stringify(loginData),
      });

      if (response.ok) {
        // Successful login, redirect or perform other actions
        console.log('Login successful');
        window.location.href = '/dashboard';
      } else {
        // Failed login
        console.error('Login failed');
      }
    } catch (error) {
      console.error('Error during login:', error);
    }
  };

  return (
    <div className="container">
      <div className="row justify-content-center mt-5">
        <div className="col-md-6">
          <div className="card">
            <div className="card-body">
              <h2 className="card-title">Login</h2>
              <form>
                <div className="mb-3">
                  <label htmlFor="email" className="form-label">
                    Email:
                  </label>
                  <input
                    type="email"
                    className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                    id="email"
                    name="email"
                    value={loginData.email}
                    onChange={handleChange}
                    required
                  />
                  {errors.email && <div className="invalid-feedback">{errors.email}</div>}
                </div>

                <div className="mb-3">
                  <label htmlFor="password" className="form-label">
                    Password:
                  </label>
                  <input
                    type="password"
                    className={`form-control ${errors.password ? 'is-invalid' : ''}`}
                    id="password"
                    name="password"
                    value={loginData.password}
                    onChange={handleChange}
                    required
                  />
                  {errors.password && <div className="invalid-feedback">{errors.password}</div>}
                </div>

                <button
                  type="button"
                  className="btn btn-primary"
                  onClick={handleLogin}
                >
                  Login
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;
