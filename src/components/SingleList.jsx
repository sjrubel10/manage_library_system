// SingleList.js
import React from 'react';
import { useParams } from 'react-router-dom';

function SingleList() {
    const { id } = useParams();

    return (
        <div>
            <h1>Single List - ID: {id}</h1>
            {/* Render your single list based on the ID */}
        </div>
    );
}

export default SingleList;
