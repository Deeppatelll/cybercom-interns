//for loop
for(let i=1; i<=5; i++)
{
    console.log("Iteration number:", i);

}
//while loop
let j=1;
while(j<=5)
{
    console.log("While loop iteration:", j);
    j++;
}
//do...while loop
let k=1;
do{
    console.log("Do...While loop iteration:", k);
    k++;
}
while(k<=5);

//for of loop
let colors = ["red", "blue", "green"];

for (let color of colors) {
  console.log(color);
}
for(let s=0;s<colors.length;s++)
{
    console.log(colors[s]);
}


//we have to use for in for objects we can use for but for array first we have to convert array into object
let userr = { name: "Deep", age: 22 };
let keys = Object.keys(userr);

for (let i = 0; i < keys.length; i++) {
  let key = keys[i];
  console.log(key, userr[key]);
}

//for..of
let colorss = ["red", "blue", "green"];

for (let color of colorss) {
  console.log(color);
}
//for of on object but its complexx.
let user = { name: "Deep", age: 22 };

for (let key of Object.keys(user)) {
    console.log(key, user[key]);
}
//for..in
let use = { name: "Deep", age: 22 };

for (let key in use) {
    console.log(key, use[key]);
}
