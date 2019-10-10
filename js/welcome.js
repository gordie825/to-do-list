const addform = document.querySelector('.add');
const list = document.querySelector('.todos');
const search = document.querySelector('.search input')

// const GENTEMPLATE = (todo,dates)=>{

//     const html =    `
//     <li class="list-group-item d-flex justify-content-between align-items-center">
//     <span>${todo}</span>&nbsp<span>${dates}</span>
//        <i class="fas fa-check"></i>
//     <i class="far fa-trash-alt delete"></i>
//     </li>`;

//     list.innerHTML  += html;

// };

function GENTEMPLATE(todo,dates) {
    
    const html =    `
    <li class="list-group-item d-flex justify-content-between align-items-center">
    <span>${todo}</span>&nbsp<span>${dates}</span>
    <i class="fas fa-check"></i>
    <i class="far fa-trash-alt delete"></i>
    </li>`;

    list.innerHTML  += html;
};
addform.addEventListener('submit', event =>{
    // event.preventDefault();
    const todo  =  addform.add.value.trim();
    const dates  = document.getElementById("duedate").value;
    console.log(todo);
    //User cannot enter empty string as an input
    //if statement below evaluates true if length of string is greater than 0.
    if(todo.length){
        GENTEMPLATE(todo,dates);
        addform.reset();
    }
    
});


// delete todos
//Add an event listener to todo list
list.addEventListener('click',event=>{

// if the what we click on (target) has the class Delete execute the code in the if statement block.
//  if(event.target.classList.contains('delete')){
//      event.target.parentElement.remove();
//  }
// });



const filterTodos = (term) =>{
    //store the list items in to an array
    //Array.from -> list children - this is the li tags in th Ul  parent tag
    Array.from(list.children)
    //filter the items in the array that do not include the term that is searched.
    .filter((todo) => !todo.textContent.includes(term))
    //for each of theses items turn their class to filtered which hides them.  i.e - display:none;
    .forEach((todo) => todo.classList.add('filtered'));

    Array.from(list.children)
    .filter((todo) => todo.textContent.includes(term))
    //remove the filtered class from the items that do match what is currently being searched. 
    .forEach((todo) => todo.classList.remove('filtered'));
    
};

//keyup event to fire a function that filters the items in the to do list when a user searches for a task. 
search.addEventListener('keyup', () => {
    const term = search.value.trim();
//filter function that will filter the list based onn what the user enters in the search input field. 
    filterTodos(term);
});

function delete_row(id)

{
 $.ajax
 ({
  type:'post',
  url:'modify.php',
  data:{
   delete_row:'delete_task',
   task_id:id,
  },
  success:function(response) {
   if(response=="success")
   {
    let task_row=document.getElementById("listitem-"+id);
    task_row.remove();
   }
  }
 });
}