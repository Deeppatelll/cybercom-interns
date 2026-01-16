//arrays

//create an array & some basic methods
let fruits = ["apple", "banana", "mango"];
console.log(fruits);
console.log(fruits[0]);
fruits[1] = "orange"; 
console.log(fruits);
fruits.push("grape");
console.log(fruits);
fruits.pop();
console.log(fruits);
fruits.shift();
console.log(fruits);
fruits.unshift("kiwi");
console.log(fruits);

//iterate over an array
for (let i = 0; i < fruits.length; i++) {
    console.log(fruits[i]);
}

//iteration over object
let obj={
    name: "Deep",
    age: 22,
    city: "ahemdabad"
}

for(let key in obj)
{
    console.log(key, obj[key]);
}

//map method
let numbers = [1, 2, 3, 4, 5];
let squared = numbers.map(
    n=> n * n
);
console.log(squared); // [1, 4, 9, 16, 25]

//filter method
let nums = [10, 25, 30, 15, 50];

let result = nums.filter(n => n > 20);

console.log(result);
// [25, 30, 50]

//reduce method
let numss = [10, 20, 30];

let sum = numss.reduce((total, n) => {
  return total + n;
}, 0);

console.log(sum); // 60

//destructuring array
let numsss = [10, 20, 30];

let [a, b, c] = numsss;
console.log(a, b, c);
//10 20 30

//destructuring object
let user = { name: "Deep", age: 22 };

let { name, age } = user;
console.log(name, age);


let arr1 = [1, 2, 3];
let arr2 = arr1;   // reference copy
arr2.push(4);

console.log(arr1); // [1, 2, 3, 4]
console.log(arr2); // [1, 2, 3, 4]

let arr3 = [...arr1];  // proper copy
arr3.push(5);
console.log(arr1); // [1, 2, 3, 4]
console.log(arr3); // [1, 2, 3, 4, 5]

//spread operator with function

function summ(x, y, z) {
    return x + y + z;
}

let numberss = [1, 2, 3];

let resultt = summ(...numberss);

console.log(resultt); // 6

//slice method
let array = [10, 20, 30, 40, 50];
let sliced = array.slice(1, 4);
console.log(sliced); // [20, 30, 40]
console.log(array); // [10, 20, 30, 40, 50]
//splice method
let arr = [10, 20, 30, 40, 50];
arr.splice(2, 1, 25, 35);
console.log(arr); // [10, 20, 25, 35, 40, 50]

//.find,.filter,.includes methods  searching in array

let arrs = [10, 20, 30];

let total = arrs.reduce((sum, n) => sum + n, 0);

console.log(total);
//reverse method
let arr22 = [1, 2, 3];
arr22.reverse();

console.log(arr22);

//reduce method
let arr33 = [1, 2, 3, 4];
let totall = arr33.reduce(function(sum, value) {
    return sum + value;
}, 0);

console.log(totall);


//array methods summary
//concate
//find,filter,includes
//map
let arrsss = [[1, 2], [3, 4]];

let resulttt = arrsss.flat();

console.log(resulttt);
// [1, 2, 3, 4]

let ss = [1, 2, 3];
let resultq= ss.map(x => [x, x * 2]);

console.log(resultq);
// [[1, 2], [2, 4], [3, 6]]
let resultqq = ss.flatMap(x => [x, x * 2]);


let arrr = [1, 2, 3, 4];
let newArr = arrr.toSpliced(1, 2);

console.log(arrr);// [1, 2, 3, 4]
console.log(newArr);// [1, 4]
