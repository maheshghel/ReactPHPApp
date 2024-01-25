import React, { useState } from 'react';


const Registration = () => {
    const [registerFormData, setRegisterFormData] = useState({
        name: '',
        email: '',
        phone: '',
        password: '',
        confirmPassword: '',
        file: null,
      });
    
      const [validationErrors, setValidationErrors] = useState({});
    
      const handleRegisterChange = (e) => {
        setRegisterFormData({
          ...registerFormData,
          [e.target.name]: e.target.value,
        });
    
        setValidationErrors({
          ...validationErrors,
          [e.target.name]: null,
        });
      };
    
      const handleFileChange = (e) => {
        setRegisterFormData({
          ...registerFormData,
          file: e.target.files[0],
        });
      };
    
      const handleRegisterSubmit = async () => {
        const errors = {};
    
        if (!registerFormData.name.trim()) {
          errors.name = 'Name is required';
        }
    
        if (!registerFormData.email.trim()) {
          errors.email = 'Email is required';
        } else if (!/\S+@\S+\.\S+/.test(registerFormData.email)) {
          errors.email = 'Invalid email format';
        }
    
        if (!registerFormData.phone.trim()) {
          errors.phone = 'Phone is required';
        }
    
        if (!registerFormData.password.trim()) {
          errors.password = 'Password is required';
        }
    
        if (registerFormData.password !== registerFormData.confirmPassword) {
          errors.confirmPassword = 'Passwords do not match';
        }
    
        setValidationErrors(errors);
    
        if (Object.keys(errors).length === 0) {
          try {
            const formData = new FormData();
            formData.append('name', registerFormData.name);
            formData.append('email', registerFormData.email);
            formData.append('phone', registerFormData.phone);
            formData.append('password', registerFormData.password);
            formData.append('confirmPassword', registerFormData.confirmPassword);
            formData.append('file', registerFormData.file);
    
            const response = await fetch('http://localhost:80/registration_api/register.php', {
              method: 'POST',
              body: formData,
            });
    
            const data = await response.json();
            alert(data.message);
            if(data.error) alert(data.error);
            setRegisterFormData({
                name: '',
                email: '',
                phone: '',
                password: '',
                confirmPassword: '',
                file: null,
              });
          } catch (error) {
            console.log(error);
            console.error(error);
          }
        }
      };
    
      return (
        <div className="container">
          <div className="row justify-content-center mt-5">
            <div className="col-md-12">
              <div className="card">
                <div className="card-body">
                  <h2 className="card-title">Register</h2>
                  <form>
                    <div className="mb-3">
                      <label htmlFor="name" className="form-label">
                        Name:
                      </label>
                      <input
                        type="text"
                        className={`form-control ${
                          validationErrors.name ? 'is-invalid' : ''
                        }`}
                        id="name"
                        name="name"
                        value={registerFormData.name}
                        onChange={handleRegisterChange}
                        required
                      />
                      {validationErrors.name && (
                        <div className="invalid-feedback">{validationErrors.name}</div>
                      )}
                    </div>
    
                    <div className="mb-3">
                      <label htmlFor="email" className="form-label">
                        Email:
                      </label>
                      <input
                        type="email"
                        className={`form-control ${
                          validationErrors.email ? 'is-invalid' : ''
                        }`}
                        id="email"
                        name="email"
                        value={registerFormData.email}
                        onChange={handleRegisterChange}
                        required
                      />
                      {validationErrors.email && (
                        <div className="invalid-feedback">{validationErrors.email}</div>
                      )}
                    </div>
    
                    <div className="mb-3">
                      <label htmlFor="phone" className="form-label">
                        Phone:
                      </label>
                      <input
                        type="tel"
                        className={`form-control ${
                          validationErrors.phone ? 'is-invalid' : ''
                        }`}
                        id="phone"
                        name="phone"
                        value={registerFormData.phone}
                        onChange={handleRegisterChange}
                        required
                      />
                      {validationErrors.phone && (
                        <div className="invalid-feedback">{validationErrors.phone}</div>
                      )}
                    </div>
    
                    <div className="mb-3">
                      <label htmlFor="password" className="form-label">
                        Password:
                      </label>
                      <input
                        type="password"
                        className={`form-control ${
                          validationErrors.password ? 'is-invalid' : ''
                        }`}
                        id="password"
                        name="password"
                        value={registerFormData.password}
                        onChange={handleRegisterChange}
                        required
                      />
                      {validationErrors.password && (
                        <div className="invalid-feedback">{validationErrors.password}</div>
                      )}
                    </div>
    
                    <div className="mb-3">
                      <label htmlFor="confirmPassword" className="form-label">
                        Confirm Password:
                      </label>
                      <input
                        type="password"
                        className={`form-control ${
                          validationErrors.confirmPassword ? 'is-invalid' : ''
                        }`}
                        id="confirmPassword"
                        name="confirmPassword"
                        value={registerFormData.confirmPassword}
                        onChange={handleRegisterChange}
                        required
                      />
                      {validationErrors.confirmPassword && (
                        <div className="invalid-feedback">
                          {validationErrors.confirmPassword}
                        </div>
                      )}
                    </div>
    
                    <div className="mb-3">
                      <label htmlFor="file" className="form-label">
                        Choose a image file:
                      </label>
                      <input
                        type="file"
                        className={`form-control ${
                          validationErrors.password ? 'is-invalid' : ''
                        }`}
                        id="file"
                        name="file"
                        accept=".jpg, .jpeg, .png"
                        onChange={handleFileChange}
                        required
                      />
                      {validationErrors.file && (
                        <div className="invalid-feedback">
                          {validationErrors.file}
                        </div>
                      )}
                    </div>
    
                    <button
                      type="button"
                      className="btn btn-primary"
                      onClick={handleRegisterSubmit}
                    >
                      Register
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      );
  };
  
  export default Registration;