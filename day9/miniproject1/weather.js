const button=document.getElementById('getWeatherBtn');
const input=document.getElementById('cityInput');

async function getData(cityName){

const promise=await fetch(`http://api.weatherapi.com/v1/current.json?key=79fdfd9b15c74891bba95824261601&q=${cityName}&aqi=yes`);
  return await promise.json();
}
button.addEventListener('click',async ()=>{
    console.log(input.value);
    const data=await getData(input.value);
    console.log(data);
});