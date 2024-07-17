// NavigationBar.js
import React from 'react';
import { Link } from 'react-router-dom';
import '../style/NavigationBar.scss'; // Import CSS file for styling

function NavigationBar() {
    return (
        <nav className="navbar">
            <ul className="navbar-list">
                <li className="navbar-item"><Link to="/" className="navbar-link">Home</Link></li>
                <li className="navbar-item"><Link to="/tasks" className="navbar-link">Books</Link></li>
                <li className="navbar-item"><Link to="/create" className="navbar-link">Create</Link></li>
                {/* Add more links as needed */}
            </ul>
        </nav>
    );
}

export default NavigationBar;
