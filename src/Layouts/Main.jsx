import React from 'react';
import NavigationBar from '../components/NavigationBar';
import { Outlet } from 'react-router-dom';
function Main() {
    return (
        <div>
            <NavigationBar/>
            <Outlet/>
        </div>
    );
}

export default Main;
