import React from 'react';

const Dashboard = () => {

    const handleLogout = () => {
        window.location.href = '/login';
      };

    return (
        <div className="container mt-5">
        <h2>Welcome to the Dashboard!</h2>

        <button className="btn btn-danger" onClick={handleLogout}>
            Logout
        </button>
        </div>
        
    );
};

export default Dashboard;
