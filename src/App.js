import React from 'react';
import { HashRouter as Router, Routes, Route, createHashRouter, RouterProvider } from 'react-router-dom';
import MultipleList from './components/MultipleTask';
import Dashboard from './components/Dashboard';
import CreateTask from './components/CreateTask';
import DisplayTask from './components/DisplayTask';
import Main from './Layouts/Main';
const routes = createHashRouter([
    {
        path:"/",
        element: <Main/>,
        children:[
            {
                path:"/",
                element:<Dashboard/>
            },
            {
                path:"/tasks",
                element:<MultipleList/>
            },
            {
                path:"/task/:id",
                // element:<SingleList/>
                element:<DisplayTask/>
            },
            {
                path:"create",
                element: <CreateTask/>,
            }
        ]
    },

])
function App() {
    return (
        <RouterProvider router={routes}/>
    );
}

export default App;
