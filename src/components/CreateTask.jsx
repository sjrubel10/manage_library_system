import React, { useState } from 'react';
import '../style/Createtas.scss';

const CreateTask = () => {
    // State variables to hold form data and popup visibility
    const [title, setTitle] = useState('');
    const [author, setAuthor] = useState('');
    const [description, setDescription] = useState('');
    const [duration, setDuration] = useState('');
    const [isbn, setIsbn] = useState('');
    const [showSuccessMessage, setShowSuccessMessage] = useState(false); // State to manage popup visibility

    // Function to handle form submission
    const handleSubmit = async (e) => {
        e.preventDefault();
        const data = {
            title: title,
            author: author,
            publisher: description,
            publication_date: duration,
            isbn: isbn,
        };

        console.log( data );

        try {
            const response = await fetch(''+myVars.site_url+'wp-json/tasktodo/v1/createtask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': myVars.rest_nonce
                },
                body: JSON.stringify(data),
            });

            const responseData = await response.json();

            // Show popup message if task is successfully created
            setShowSuccessMessage(true);

            // Clear form fields
            setTitle('');
            setDescription('');
            setDuration('');
            setIsbn('');
            setAuthor('');
        } catch (error) {
            console.error('Error:', error);
        }
    };

    // Function to handle input focus
    const handleInputFocus = () => {
        setShowSuccessMessage( false );
    };

    return (
        <div className="taskToDoform-container">
            <h1>Create Task</h1>
            <form onSubmit={handleSubmit}>
                <label>Title:</label>
                <input
                    type="text"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    onFocus={handleInputFocus} // Call handleInputFocus function when input is focused
                    required
                />

                <label>Author:</label>
                <input
                    type="text"
                    value={author}
                    onChange={(e) => setAuthor(e.target.value)}
                    onFocus={handleInputFocus} // Call handleInputFocus function when input is focused
                    required
                />

                <label>Publisher:</label>
                <textarea
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    onFocus={handleInputFocus} // Call handleInputFocus function when input is focused
                    required
                />

                <label>Publication date:</label>
                <input
                    type="datetime-local"
                    value={duration}
                    onChange={(e) => setDuration(e.target.value)}
                    onFocus={handleInputFocus} // Call handleInputFocus function when input is focused
                    required
                />

                <label>ISBN:</label>
                <input
                    type="text"
                    value={isbn}
                    onChange={(e) => setIsbn(e.target.value)}
                    onFocus={handleInputFocus} // Call handleInputFocus function when input is focused
                />

                <button type="submit">Crete Book</button>
            </form>

            {/* Popup message */}
            {showSuccessMessage && (
                <div className="popup-message">
                    <p>Task successfully created!</p>
                </div>
            )}
        </div>
    );
};

export default CreateTask;
