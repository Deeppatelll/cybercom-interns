//operators in js.

//assignment operators(num)
let a = 10;
let b = 5;
let c =a+ b; 
console.log(c);
let d=a**b;
console.log(d); 
let e=a-b;
console.log(e);
let f=a/b;
console.log(f);
let g=a%b;
console.log(g);

//assignment operators(str)
let str1="Hello";
let str2="world";
str1+=str2;
console.log(str1);
let x = true;
let y = x &&= 10;
console.log(y);
let p = false;
let q= p &&= 5;
console.log(q);
let r = 1;
let s = r ||= 10;
console.log(s);
let t = 0;
let u = t ||= 5;
console.log(u);

//comparison operators
let m=5;
console.log(m==5);
console.log(m=="5");
console.log(m===5);
console.log(m!=5);
console.log(m!==5);
console.log(m!="5")
console.log(m!=="5");

let text1 = "20";
let text2 = "5";
let result = text1 < text2;
console.log(result);

//arithmetic operators
let num1 = 15;
let num2 = 4;
console.log(num1 + num2);
console.log(num1 - num2);
console.log(num1 * num2);
console.log(num1 / num2);
console.log(num1 % num2);
console.log(num1 ** num2);