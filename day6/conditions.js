//normal if statement
let age = "25";
let country = "USA";
let text = "You can Not drive";

if (country == "USA" && age >= 16) {
  text = "You can drive";
}
console.log(text);


//multiple if statement
let agee = 22;
let hasID = true;
let hasTicket = false;
console.log("start");
if (agee >= 18) {
  console.log("Age is 18 or above");
}

if (hasID) {
  console.log("User has ID");
}

if (hasTicket) {
  console.log("User has ticket");
}


//if...else statementt
let time = 20;
let greeting;
if (time < 18) {
    greeting = "Good day";
}
else {
    greeting = "Good evening";
}
console.log(greeting);


// multiple if else statements
if(typeof (age) !== 'number'){
    convertedAge = Number(age);
    console.log("Age converted to number:", convertedAge);
}

else if (age >= 18 && hasID && hasTicket) {
  console.log("Entry allowed");
}
else if (age >= 18 && hasID && !hasTicket) {
  console.log("Entry denied: No ticket");
}
else {
  console.log("Entry denied: No ID");
}

let value = 10;


// Nested condition
if (agee >= 18) {
  if (hasTicket) {
    console.log("Can enter hall");
  } else {
    console.log("Cannot enter hall without ticket");
  }
}

console.log("End of conditions");

//ternary operator
let score=85;
let grade=(score>=90)?"Grade A":
          (score>=80)?"Grade B":
          (score>=70)?"Grade C":
          (score>=60)?"Grade D":"Grade F";
console.log(grade);

//switch statement

let marks = 68;

switch (true) {
  case marks >= 90:
    console.log("Grade A");
    break;

  case marks >= 75:
    console.log("Grade B (Pass)");
    break;

  case marks >= 60:
    console.log("Grade C");
    break;

  default:
    console.log("Fail");
}


