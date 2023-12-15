import './bootstrap';
import React from 'react';
import Login from './components/login.js';
import Register from './components/Register';

const App = () => {
    return (
        <div>
            <Login />
            <Register />
        </div>
    );
};

export default App;

