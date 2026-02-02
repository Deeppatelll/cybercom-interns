//declaration of an object
let student = {
  name: "Deep",
  age: 21,
  course: "CS",
  "college":"ddu"
};
console.log(student.age);
console.log(student["college"]);
//modifying object properties
student.age = 22;
console.log("Updated age:", student.age);
//adding new property
student.year = "Final Year";
console.log("Added year:", student.year);


//nested object
let employee = {
  name: "deep",
  position: "Developer",
  address: {
    street: "Main Street",
    city: "Metropolis",
    zip: "12345"
  }
};
console.log("Employee City:", employee.address.city);


//object with method
let calculator = {
    add: function(a, b) {
        if(isNaN(a) || isNaN(b)) {
            throw new Error("Invalid input");
        }
        return a + b;
    },
    subtract: function(a, b) {
        return a - b;
    }
};
console.log("Addition:", calculator.add(5, 3));
console.log("Subtraction:", calculator.subtract(10,4));

//function inside object using arrow function and regular function
let obj = {
  name: "Deep",
  show: function () {
    const inner = () => {
      console.log(this.name);
    };

    inner();
  }
};

obj.show();   // Deep

let objj = {
  name: "Deep",
  showw: function () {
    function inner() {
      console.log(this.name);
    }

    inner();
  }
};

objj.showw();   // undefined


//object passed to function 

function hello(user){
    console.log(user.name);
}
 hello({name: "Deep"});


 //function inside object using this keyword
 let user = {
  name: "Deep",
  login: function () {
    console.log(this.name + " logged in");
  }
};

user.login();

//pass by value
function changeValue(x) {
  x = 20;
}

let a = 10;
changeValue(a);

console.log(a); // 10
//when number of arguments are unknown
function total(...numbers) {
    let sum = 0;
    for (let n of numbers) {
        sum += n;
    }
    return sum;
}

total(1, 2, 3, 4); // 10

