import React, { useState, useEffect } from 'react';
import '../style/EditPopup.scss';

const EditPopup = ({ onClose, list }) => {
    const [title, setTitle] = useState('');
    const [publisher, setPublisher] = useState('');
    const [author, setAuthor] = useState('');
    const [isbn, setIsbn] = useState('');
    const [publication_date, setPublication_date] = useState('');
    const [editDone, setEditDone] = useState(false); // State to track if edit is done

    useEffect(() => {
        // Set initial values for title, description, and selectedOption when list data changes
        if (list) {
            setTitle(list.title || '');
            setAuthor(list.author || '');
            setPublisher(list.publisher || '');
            setIsbn(list.isbn || '');
            setPublication_date(list.publication_date || '');
        }
    }, [list]); // Run this effect when list data changes

    const handleSubmit = async (e) => {
        e.preventDefault();
        const data = {
            boo_id: list.book_id,
            title: title,
            publisher: publisher,
            author: author,
            publication_date: publication_date,
            isbn: isbn,
        };

        console.log( data );
        try {
            const response = await fetch(''+myVars.site_url+'wp-json/tasktodo/v1/editbook', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': myVars.rest_nonce
                },
                body: JSON.stringify(data),
            });

            const responseData = await response.json();
            setEditDone(true); // Set editDone to true after successful update
        } catch (error) {
            console.error('Error:', error);
        }
    };

    // Close the popup when edit is done
    useEffect(() => {
        if (editDone) {
            onClose();
        }
    }, [editDone, onClose]);

    return (
        <div className="popup-container">
            <div className="popup">
                <button className="close-btn-background close-button" onClick={onClose}>X</button> {/* Close button */}
                <h2 className="edit-post-title-text">Edit Book</h2>
                <input
                    type="text"
                    placeholder="Title"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                />
                <input
                    type="text"
                    placeholder="Author"
                    value={author}
                    onChange={(e) => setAuthor(e.target.value)}
                />
                <textarea
                    className="description_box"
                    placeholder="Description"
                    value={publisher}
                    onChange={(e) => setPublisher(e.target.value)}
                ></textarea>

                <input
                    type="datetime"
                    value={publication_date}
                    onChange={(e) => setPublication_date(e.target.value)}
                    required
                />

                <input
                    type="text"
                    value={isbn}
                    onChange={(e) => setIsbn(e.target.value)}
                    required
                />


                <button className="submit-btn-background" onClick={handleSubmit}>Update Book</button>
            </div>
        </div>
    );
};

export default EditPopup;
