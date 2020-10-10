import { url } from "./config";
import { token } from "./config";

export async function get(peticion) {
  let respuesta = await fetch(url + peticion, {
    headers: { Authorization: await getToken() },
  });
  if (!respuesta.ok) throw new Error(respuesta.text);

  return respuesta.json();
}

async function call(peticion, datos, method) {
  let respuesta = await fetch(url + peticion, {
    method,
    body: JSON.stringify(datos),
    headers: {
      "Content-Type": "application/json",
      Authorization: await getToken(),
    },
  });
  if (!respuesta.ok) throw new Error(respuesta.text);
  return respuesta.json();
}

export async function post(peticion, datos) {
  return call(peticion, datos, "POST");
}

export async function put(peticion, datos) {
  return call(peticion, datos, "PUT");
}

export async function patch(peticion, datos) {
  return call(peticion, datos, "PATCH");
}

export async function del(peticion) {
  return await fetch(url + peticion, {
    method: "DELETE",
    headers: {
      Authorization: await getToken(),
    },
  });
}
export async function enviarConArchivos(peticion, datos) {
  let respuesta = await fetch(url + peticion, {
    method: "POST",
    body: datos,
    headers: {
      Authorization: await getToken(),
    },
  });
  if (!respuesta.ok) throw new Error(respuesta.text);

  return respuesta.json();
}

export async function getToken() {
  console.log(await fetch(token));
  let respuesta = await fetch(token);
  return respuesta.json();
}
