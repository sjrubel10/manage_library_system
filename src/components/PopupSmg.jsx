import React from 'react';
import '../style/PopupSmg.scss';

const PopupSmg = ({ message, onClose }) => {
    return (
        <div className="taskToDo-popup-overlay">
            <div className="taskToDo-popup">
                {/*<span className="taskToDo-close" onClick={onClose}>&times;</span>*/}
                <div className="taskToDo-popup-content">
                    <p>{message}</p>
                </div>
            </div>
        </div>
    );
};

export default PopupSmg;
