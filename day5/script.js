//diffrance in let,const and var

//1.re-declaration
console.log("Re-declaration");

var a = 10;
var a = 20;
console.log("var a:", a); // 20

let b = 10;
//let b = 20 error
console.log("let b:", b);//20

const c = 10;
//const c=40 error
console.log("const c:", c);


//2.re-assignment
console.log("\n Re-assignment");

var x = 5;
x = 15;
console.log("var x:", x);

let y = 5;
y = 15;
console.log("let y:", y);

const z = 5;
// z = 15 error
console.log("const z:", z);


//3.scope
console.log("\n Scope");
{
    var m=50;
}

console.log(m);
{
    let n=60;
}
//console.log(n) error

{
    const p=70;
}
//console.log(p) error


//4.hoisting
console.log("\n Hoisting");

console.log(p);//undefined
var p = 100;

// console.log(q) ERROR
let q = 200;

// console.log(r) ERROR
const r = 300;

