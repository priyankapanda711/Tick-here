let button=document.getElementById('sign_in') as HTMLButtonElement;

button?.addEventListener('click',async function (e) {
  e.preventDefault();
  const form=document.getElementById('form') as HTMLFormElement;
  console.log(form);
 
  const formData= new FormData(form);
  const password = formData.get('password');
  const email = formData.get('email');

  console.log(password);
  console.log(email);
  
  // const res = await fetch('http://localhost:8000/login', {
  //   method: 'POST',
  //   headers: {
  //     'Content-Type': 'application/json',
  //     'X-CSRF-TOKEN': token,
  //   },
  //   credentials: 'include',
  //   body: JSON.stringify({
  //     email,
  //     password,
  //   }),
  // });
  
})