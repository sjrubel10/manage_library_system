import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import axios from 'axios';
import '../style/Multitask.scss';
import EditPopup from '../components/EditPopup';
import PopupSmg from '../components/PopupSmg';
import { memo } from 'react';

function MultipleBook() {
    const [lists, setLists] = useState([]);
    const [selectedList, setSelectedList] = useState(null); // State to hold selected list data

    const [showPopupSmg, setShowPopupSmg] = useState(false);
    const [loading, setLoading] = useState(false);
    const [successMessage, setSuccessMessage] = useState('');
    const [searchValue, setSearchValue] = useState('');
    const [pagination, setPagination] = useState('');
    const [limit, setLimit] = useState(2);
    const [allbooks, setAllbooks] = useState(0);
    const [numbers, setNumbers] = useState([]);

    useEffect(() => {
        const fetchData = async () => {
            setLoading(true);
            const status = 'pending';
            // const limit = 2;
            const page  = 1;
            try {
                const response = await axios.get(''+myVars.site_url+'wp-json/tasktodo/v1/books',{
                    method: 'GET',
                        headers: {
                        'Content-Type': 'application/json',
                            'X-WP-Nonce': myVars.rest_nonce
                    },
                    params: {
                        limit: limit,
                        page: page,
                    },
                });
                // Set the fetched data to the state
                const result = response.data;
                setPagination( result.total_pages );
                setNumbers( result.pages_in_ary );

                // console.log( result.total_pages );

                setLists(result.data);
                setAllbooks( 1 );
                setLoading(false);
            } catch (error) {
                setLoading( false );
                console.error('Error fetching data:', error);
            }
        };

        fetchData();
    }, []);

    const handleDelete = async ( list ) => {
        setSuccessMessage( 'Your Task '+list.post_title+' Is Deleting...' );
        // console.log( list );
        let id = list.book_id;
        setShowPopupSmg(true);
        const data = {
            id : id
        };
        try {
            const response = await fetch(''+myVars.site_url+'wp-json/tasktodo/v1/deletebook', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': myVars.rest_nonce
                },
                body: JSON.stringify(data),
            });

            const responseData = await response.json();
            setSuccessMessage( responseData );
            let divToHide = document.getElementById(id);
            divToHide.style.display = "none";
            setShowPopupSmg(false );
            setSuccessMessage('');
        } catch (error) {
            console.error('Error:', error);
        }
    };

    const handleSearchSubmit = async (e) => {
        e.preventDefault();
        const data = {
            searchValue: searchValue,
            limit: 10,
            page: 1,
        };
        setAllbooks( 0 );
        try {
            const response = await axios.get(`${myVars.site_url}wp-json/tasktodo/v1/search`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': myVars.rest_nonce
                },
                params: data
            });

            setLists(response.data);
        } catch (error) {
            console.error('Error:', error);
        }
    };

    const handleEdit = (list) => {
        setSelectedList(list);
    };


    const removeClassFromSiblings = (element, className) => {
        if (!element) return;
        const siblings = element.parentElement.children;
        for (let sibling of siblings) {
            if (sibling !== element) {
                sibling.classList.remove(className);
            }
        }
    };
    const handlePagination = async (pageNumber) => {
        try {
            const response = await axios.get(''+myVars.site_url+'wp-json/tasktodo/v1/books',{
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': myVars.rest_nonce
                },
                params: {
                    limit: limit,
                    page: pageNumber,
                },
            });
            // Set the fetched data to the state
            const result = response.data;
            //selectedPage
            setLists(result.data);
            setLoading(false);

            let clickedId = pageNumber+'LMSPage';
            const element = document.getElementById(clickedId);
            if (element) {
                element.classList.add('selectedPage');
                // Remove the class from all siblings
                removeClassFromSiblings(element, 'selectedPage');
            }

        } catch (error) {
            setLoading( false );
            console.error('Error fetching data:', error);
        }
    };

    if( loading ){
       return <>
           <h1 className="taskToDomultiTaskTitle">Multiple Books</h1>
           <h3 className="taskToDomultiTaskTitle" id="loadingSmg">Loading...</h3>
       </>
    }

    // console.log( lists );

    return (
        <div>
            <div className="headerTopHolder">
                <h1 className="taskToDomultiTaskTitle">Multiple Lists</h1>
                <div className="searchFieldHolder">
                    <div className="wrap">
                        <div className="booksearchHolder" id="booksearchHolder">
                            <input
                                type="text"
                                placeholder="Search Something"
                                value={searchValue}
                                onChange={(e) => setSearchValue(e.target.value)}
                            />
                            <button type="submit" className="searchButton" onClick={handleSearchSubmit}>Search</button>
                        </div>
                    </div>
                </div>

            </div>
            <ul className="booksHolder">
                { lists && lists.length > 0 ? (
                    lists.map(list => (
                        <>
                            <li key={list.book_id} id={list.book_id}>
                                <div className="taskToDoCard">
                                    <Link to={`/task/${list.book_id}`} >
                                        <p className="taskToDoCard-title"> {list.title}</p>
                                    </Link>

                                    <p className="taskToDoCard-description"><span className="bookInfoTitle">book author</span> : {list.author}</p>
                                    <p className="taskToDoCard-description"><span className="bookInfoTitle">book isbn</span> : {list.isbn}</p>
                                    <p className="taskToDoCard-description"><span className="bookInfoTitle">book publisher</span> : {list.publisher}</p>
                                    <p className="taskToDoCard-date"><span className="bookInfoTitle">Date</span> : {list.publication_date}</p>
                                    <div className="taskToDoCard-buttons">
                                        {/* Call handleEdit with list data when Edit button is clicked */}
                                        <button className="taskToDoEdit-button" onClick={() => handleEdit(list)}>Edit</button>
                                        <button className="taskToDoDelete-button" onClick={() => handleDelete( list )}>Delete</button>
                                    </div>
                                </div>
                            </li>
                        </>
                    ))
                ) : (
                    <li>
                        <p>No data found</p>
                    </li>
                )}
                { pagination > 0 && allbooks > 0 ? (
                        <div className="paginationHolder">
                            {/* Loop through the numbers array and generate <p> tags */}
                            { numbers.map((num, index) => (
                                <p
                                    key={num}
                                    id = {`${num}LMSPage`}
                                    className={`pagination ${index === 0 ? 'selectedPage' : ''}`}
                                    onClick={() => handlePagination(num)}
                                >
                                    {num}
                                </p>
                            ))}
                        </div>
                    // <div className="loadmoreButtonHolder"> {pagination}</div>
                ) : (
                    <div className="loadmoreButtonHolder"> </div>
                )}

            </ul>

            {/* Render EditPopup component with selectedList as prop */}
            {selectedList && <EditPopup list={selectedList} onClose={() => setSelectedList(null)} />}
            { showPopupSmg &&  <PopupSmg message = { successMessage } onClose={() => setSelectedList(null)}/>}
        </div>
    );
}

export default memo( MultipleBook );
