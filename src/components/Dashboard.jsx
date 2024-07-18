import React from 'react'

const Dashboard = () => {
    return (
        <div className='dashboard'>
            <div className="taskToDoCard">
                <h1>Dashboard</h1>
                <h2>Title: Library manager Plugin Desciption</h2>
                <h4>Description:</h4>
                <div>
                    <p>
                        Welcome to our Library Manager App, designed to streamline your productivity and keep your tasks organized.
                    </p>
                    <b>Main Features:</b>
                    <p>
                    <span>How To use</span>
                    After Installation you can see a <b>Manage Library System</b> menu in menu bar click this menu and get other functionalities.
                    Book Creation: Click the "<b>Create</b>" button to add a new book to your list. Enter the book details such as title, description, date, author, and any additional notes. Once you've filled in the necessary information, simply hit "Save" to add the book to your list.
                    </p>
                    <p>
                    Show All Books Tab: Navigate to the "Show All <b>Books</b>" tab to view a comprehensive list of all your books. Here, you can easily see the details of each task including its title, author, isbn, and publisher. You can also edit, delete and search books directly from this tab.
                    </p>
                    <b>Additional Features:</b>
                    This is crud operation app
                    <p>
                    With our Library Manager App, you'll have all the tools you need to stay organized, productive, and focused on achieving your goals.
                        Whether you're managing personal tasks, work projects, or team assignments, our app is your go-to solution for efficient task management.
                        Try it out today and experience the difference it can make in your productivity!
                    </p>
            </div>
            </div>
        </div>
    );
}

export default Dashboard;