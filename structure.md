# High Level

User defines tasks a task may contain:

- one or more rules
- one or more actions

If all rules pass then all actions will be ran

## Maxwell Format
```json
  {
  "database":"cmsapp",
  "table":"cms_settings",
  "type":"update",
  "ts":1471783160,
  "xid":293,
  "commit":true,
  "data":{"id":1,"path":"key1","setting_vale":"fee"},
  "old":{"setting_vale":"ree"}
  }

  {
  "database":"cmsapp",
  "table":"cms_settings",
  "type":"insert",
  "ts":1471783231,
  "xid":439,
  "commit":true,
  "data":{"id":3,"path":"key3","setting_vale":"bar"}
  }
```