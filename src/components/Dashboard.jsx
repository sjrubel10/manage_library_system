import React from 'react'

const Dashboard = () => {
    return (
        <div className='dashboard'>
            <div className="taskToDoCard">
                <h1>Dashboard</h1>
                <h2>Title: Task Manager App Description</h2>
                <h4>Description:</h4>
                <div>
                    <p>
                        Welcome to our Task Manager App, designed to streamline your productivity and keep your tasks organized. With a simple interface and intuitive features, managing your daily to-dos has never been easier.
                    </p>
                    <b>Main Features:</b>
                    <p>
                    Task Creation: Click the "Task <b>Create</b>" button to add a new task to your list. Enter the task details such as title, description, due date, priority, and any additional notes. Once you've filled in the necessary information, simply hit "Save" to add the task to your list.
                    </p>
                    <p>
                    Show All Tasks Tab: Navigate to the "Show All <b>Tasks</b>" tab to view a comprehensive list of all your tasks. Here, you can easily see the details of each task including its title, due date, priority, and status. You can also edit or delete tasks directly from this tab.
                    </p>
                    <b>Additional Features:</b>

                    Task Filtering: Easily filter tasks based on their status (e.g., pending, completed, overdue) or priority level to focus on what's most important.

                    Task Sorting: Sort tasks by due date, priority, or alphabetically to better organize your workflow.

                    Task Reminders: Set reminders for important tasks to ensure you never miss a deadline.

                    Task Categories: Organize tasks into different categories or projects to better manage and prioritize your workload.

                    Task Collaboration: Share tasks with team members or collaborators to delegate work and track progress together.

                    Task Notifications: Receive notifications for upcoming deadlines or task updates to stay on top of your commitments.
                    <p>
                    With our Task Manager App, you'll have all the tools you need to stay organized, productive, and focused on achieving your goals. Whether you're managing personal tasks, work projects, or team assignments, our app is your go-to solution for efficient task management. Try it out today and experience the difference it can make in your productivity!
                    </p>
            </div>
            </div>
        </div>
    );
}

export default Dashboard;