let a = true;
let b = true;
console.log();

//Template Literals
let firstName "Sangam";
let surName = "Mukherjee";
let prefix = "Mr";
console.log(prefix + + firstName + surName);
console.log(`${prefix} ${firstName} ${surName}`); I
//Ternary Operators
let returnAge = false;
function getAgeInfo(age) {
return `This person is ${age} years old`
}|
I
function getCity(){
return 'This person is from USA'
}
if(returnAge) {
console.log (getAgeInfo(30))
} else {
console.log (getCity())
}

returnAge ? console.log(getAgeInfo(50)) : console.log(gel 

// Shorthand Property Names
const id = 1;
const title = 'Product 1';
const rating = 5;
const product = {
id : id,
title : title,
rating: rating
}
console.log(product);

//Same
const product = {
    id,
    title,
    rating
}

const productOne= {
    productName: 'Product One',
    product Description : 'Product Description'
    }
    let productName = productOne.productName;
    let product Description productOne.product Description
    console.log(productName, product Description);

    const {productName, productDescription} = productone;

    let arr = ['john', 'doe', 'random'];
// let arrFirstElement = arr[0]
// let arrSecondElement = arr[1]
let [arrFirstElement, arrSecond Element xyz] = arr;
console.log(arrFirstElement, arrSecond Element xyz );

// Default Parameters
function sum (numone = 1, numTwo = 2) {
    return numOne + numTwo
    }
    let result = sum(); I
    console.log(result, 'result');

    //spread operators, rest opeartors
const arrone = [1,2,3];
const arrTwo = [4,5,6]
console.log([...arrone, 100,... arrTwo]);

function someThing (a, b,c) {
    console.log(a,b,c);
    return 'Hello'
    console.log(someThing(1,2,3,4,5));

function someThing (a,b,c,...d) {
    console.log(a,b,c,d);
    return 'Hello'
    } I
        console.log (someThing (1,2,3,4,5,6,7));

        const person={
            name:"person1",
            age:"35"
        }
    let extractPersonNames = personArray.map((person,index)=>
    {return person.name})

    console.log(extractPersonNames);
    //0:person1 .....
    //...
     
    let extractPersonNames = personArray.map(
        (person,index) => '${person.name}-${person.city}');
    console.log(extractPersonNames);  