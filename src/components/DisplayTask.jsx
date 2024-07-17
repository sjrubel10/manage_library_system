import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import '../style/DisplayList.scss';
import axios from "axios";

function DisplayTask() {
    const { id } = useParams();
    // const  id  = 1;

    const [task, setTask] = useState(null);
    // const [status, setStatus] = useState('');

    useEffect(() => {
        const fetchTask = async () => {
            const data1 = {
                postId: id,
            };
            try {
                const response = await axios.get(`${myVars.site_url}wp-json/tasktodo/v1/book?id=${id}`,{
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': myVars.rest_nonce
                    },
                });

                const resultData = response.data.data;

                setTask( resultData );
                // setStatus( resultData.post_status );
            } catch (error) {
                console.error('Error fetching task:', error);
            }
        };

        fetchTask();
    }, [id]);

    return (
        <div className="taskToDo-single-list">
            {task ? (
                <div>
                    <h1 className="taskToDo-task-title">{task.title}</h1>
                    <p className="taskToDo-task-author"><strong>Creator:</strong> {task.author}</p>
                    <p className="taskToDo-task-description"><strong>Description:</strong> {task.publisher}</p>
                    <p className="taskToDo-task-create-date"><strong>Create Date:</strong> {task.publication_date}</p>
                    <p className="taskToDo-task-validity-date"><strong>Validity Date:</strong> {task.isbn}</p>

                </div>
            ) : (
                <p className="taskToDo-loading">Loading...</p>
            )}
        </div>
    );
}

export default DisplayTask;
