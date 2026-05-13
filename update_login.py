import json

with open('workflows/EduBook - Login.json', 'r', encoding='utf-8') as f:
    wf = json.load(f)

# Create the new nodes
supabase_get_perfil = {
  "parameters": {
    "method": "GET",
    "url": "={{ 'https://vibsrorignwbcwqbuten.supabase.co/rest/v1/perfiles?select=*&user_email=eq.' + $json.user_email }}",
    "sendHeaders": true,
    "headerParameters": {
      "parameters": [
        { "name": "apikey", "value": "sb_publishable_uawYeXSNpPQHOA-RR2JGXg_EBvbXhhz" },
        { "name": "Authorization", "value": "Bearer sb_publishable_uawYeXSNpPQHOA-RR2JGXg_EBvbXhhz" }
      ]
    },
    "options": { "response": { "response": { "neverError": true } } }
  },
  "id": "supabase-get-perfil",
  "name": "Supabase Get Perfil",
  "type": "n8n-nodes-base.httpRequest",
  "typeVersion": 4.2,
  "position": [ 224, 0 ]
}

merge_perfil = {
  "parameters": {
    "jsCode": "const authUser = $('Validar Respuesta Login').item.json;\nconst dbPerfil = $json.length > 0 ? $json[0] : {};\n\nreturn [{ json: {\n  login_ok: true,\n  access_token: authUser.access_token,\n  expires_in: authUser.expires_in,\n  user_id: authUser.user_id,\n  user_email: authUser.user_email,\n  nombre: dbPerfil.nombre || authUser.nombre,\n  universidad: dbPerfil.universidad || authUser.universidad,\n  rol: dbPerfil.rol || authUser.rol,\n  telefono: dbPerfil.telefono || '',\n  id_estudiante: dbPerfil.id_estudiante || '',\n  fecha_nacimiento: dbPerfil.fecha_nacimiento || '',\n  avatar: dbPerfil.avatar || ''\n}}];"
  },
  "id": "merge-perfil",
  "name": "Merge Perfil",
  "type": "n8n-nodes-base.code",
  "typeVersion": 2,
  "position": [ 448, 0 ]
}

# Update positions
for node in wf['nodes']:
    if node['name'] == 'Respuesta OK':
        node['position'] = [ 672, 0 ]
        # Update response to include new fields
        node['parameters']['responseBody'] = "={\n  \"status\": \"ok\",\n  \"access_token\": \"{{ $json.access_token }}\",\n  \"expires_in\": {{ $json.expires_in }},\n  \"user\": {\n    \"id\":          \"{{ $json.user_id }}\",\n    \"email\":       \"{{ $json.user_email }}\",\n    \"nombre\":      \"{{ $json.nombre }}\",\n    \"universidad\": \"{{ $json.universidad }}\",\n    \"rol\":         \"{{ $json.rol }}\",\n    \"telefono\":    \"{{ $json.telefono }}\",\n    \"id_estudiante\": \"{{ $json.id_estudiante }}\",\n    \"fecha_nacimiento\": \"{{ $json.fecha_nacimiento }}\",\n    \"avatar\":      \"{{ $json.avatar }}\"\n  }\n}"

wf['nodes'].append(supabase_get_perfil)
wf['nodes'].append(merge_perfil)

# Update connections
wf['connections']['¿Login correcto?']['main'][0] = [{ "node": "Supabase Get Perfil", "type": "main", "index": 0 }]

wf['connections']['Supabase Get Perfil'] = {
  "main": [ [ { "node": "Merge Perfil", "type": "main", "index": 0 } ] ]
}

wf['connections']['Merge Perfil'] = {
  "main": [ [ { "node": "Respuesta OK", "type": "main", "index": 0 } ] ]
}

with open('workflows/EduBook - Login.json', 'w', encoding='utf-8') as f:
    json.dump(wf, f, indent=2, ensure_ascii=False)
