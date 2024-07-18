import React from 'react';
import { HashRouter as Router, Routes, Route, createHashRouter, RouterProvider } from 'react-router-dom';
import MultipleList from './components/MultipleBook';
import Dashboard from './components/Dashboard';
import CreateBook from './components/CreateBook';
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
                path:"/books",
                element:<MultipleList/>
            },
            {
                path:"/task/:id",
                // element:<SingleList/>
                element:<DisplayTask/>
            },
            {
                path:"create",
                element: <CreateBook/>,
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
