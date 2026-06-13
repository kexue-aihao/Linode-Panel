# Linode API v4 全量端点索引

来源：Linode 官方 OpenAPI 规范 `linode/linode-api-openapi`，标题 `Akamai: Linode API`，版本 `4.229.1`。

说明：实际请求时把下方路径中的 `/v4` 作为稳定 API 版本；仍在 Beta 的操作可按官方说明使用 `/v4beta`。

总计：`334` 个路径，`505` 个 HTTP 操作。

## Access keys

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Access keys; Method=GET; Path=/v4/object-storage/keys; OperationId=get-object-storage-keys; Summary=List Object Storage access keys; Deprecated=}.Path) | $(@{Tag=Access keys; Method=GET; Path=/v4/object-storage/keys; OperationId=get-object-storage-keys; Summary=List Object Storage access keys; Deprecated=}.OperationId) | List Object Storage access keys |
| POST | $(@{Tag=Access keys; Method=POST; Path=/v4/object-storage/keys; OperationId=post-object-storage-keys; Summary=Create an Object Storage access key; Deprecated=}.Path) | $(@{Tag=Access keys; Method=POST; Path=/v4/object-storage/keys; OperationId=post-object-storage-keys; Summary=Create an Object Storage access key; Deprecated=}.OperationId) | Create an Object Storage access key |
| DELETE | $(@{Tag=Access keys; Method=DELETE; Path=/v4/object-storage/keys/{keyId}; OperationId=delete-object-storage-key; Summary=Revoke an Object Storage access key; Deprecated=}.Path) | $(@{Tag=Access keys; Method=DELETE; Path=/v4/object-storage/keys/{keyId}; OperationId=delete-object-storage-key; Summary=Revoke an Object Storage access key; Deprecated=}.OperationId) | Revoke an Object Storage access key |
| GET | $(@{Tag=Access keys; Method=GET; Path=/v4/object-storage/keys/{keyId}; OperationId=get-object-storage-key; Summary=Get an Object Storage access key; Deprecated=}.Path) | $(@{Tag=Access keys; Method=GET; Path=/v4/object-storage/keys/{keyId}; OperationId=get-object-storage-key; Summary=Get an Object Storage access key; Deprecated=}.OperationId) | Get an Object Storage access key |
| PUT | $(@{Tag=Access keys; Method=PUT; Path=/v4/object-storage/keys/{keyId}; OperationId=put-object-storage-key; Summary=Update an Object Storage access key; Deprecated=}.Path) | $(@{Tag=Access keys; Method=PUT; Path=/v4/object-storage/keys/{keyId}; OperationId=put-object-storage-key; Summary=Update an Object Storage access key; Deprecated=}.OperationId) | Update an Object Storage access key |

## Account

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Account; Method=GET; Path=/v4/account; OperationId=get-account; Summary=Get your account; Deprecated=}.Path) | $(@{Tag=Account; Method=GET; Path=/v4/account; OperationId=get-account; Summary=Get your account; Deprecated=}.OperationId) | Get your account |
| PUT | $(@{Tag=Account; Method=PUT; Path=/v4/account; OperationId=put-account; Summary=Update your account; Deprecated=}.Path) | $(@{Tag=Account; Method=PUT; Path=/v4/account; OperationId=put-account; Summary=Update your account; Deprecated=}.OperationId) | Update your account |
| POST | $(@{Tag=Account; Method=POST; Path=/v4/account/cancel; OperationId=post-cancel-account; Summary=Delete your account; Deprecated=}.Path) | $(@{Tag=Account; Method=POST; Path=/v4/account/cancel; OperationId=post-cancel-account; Summary=Delete your account; Deprecated=}.OperationId) | Delete your account |

## Account agreements

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Account agreements; Method=GET; Path=/v4/account/agreements; OperationId=get-account-agreements; Summary=List agreements; Deprecated=}.Path) | $(@{Tag=Account agreements; Method=GET; Path=/v4/account/agreements; OperationId=get-account-agreements; Summary=List agreements; Deprecated=}.OperationId) | List agreements |
| POST | $(@{Tag=Account agreements; Method=POST; Path=/v4/account/agreements; OperationId=post-account-agreements; Summary=Acknowledge agreements; Deprecated=}.Path) | $(@{Tag=Account agreements; Method=POST; Path=/v4/account/agreements; OperationId=post-account-agreements; Summary=Acknowledge agreements; Deprecated=}.OperationId) | Acknowledge agreements |

## Account availability

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Account availability; Method=GET; Path=/v4/account/availability; OperationId=get-availability; Summary=List available services; Deprecated=}.Path) | $(@{Tag=Account availability; Method=GET; Path=/v4/account/availability; OperationId=get-availability; Summary=List available services; Deprecated=}.OperationId) | List available services |
| GET | $(@{Tag=Account availability; Method=GET; Path=/v4/account/availability/{regionId}; OperationId=get-account-availability; Summary=Get available services for a region; Deprecated=}.Path) | $(@{Tag=Account availability; Method=GET; Path=/v4/account/availability/{regionId}; OperationId=get-account-availability; Summary=Get available services for a region; Deprecated=}.OperationId) | Get available services for a region |

## Account logins

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Account logins; Method=GET; Path=/v4/account/logins; OperationId=get-account-logins; Summary=List user logins; Deprecated=}.Path) | $(@{Tag=Account logins; Method=GET; Path=/v4/account/logins; OperationId=get-account-logins; Summary=List user logins; Deprecated=}.OperationId) | List user logins |
| GET | $(@{Tag=Account logins; Method=GET; Path=/v4/account/logins/{loginId}; OperationId=get-account-login; Summary=Get an account login; Deprecated=}.Path) | $(@{Tag=Account logins; Method=GET; Path=/v4/account/logins/{loginId}; OperationId=get-account-login; Summary=Get an account login; Deprecated=}.OperationId) | Get an account login |

## Account settings

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Account settings; Method=GET; Path=/v4/account/settings; OperationId=get-account-settings; Summary=Get account settings; Deprecated=}.Path) | $(@{Tag=Account settings; Method=GET; Path=/v4/account/settings; OperationId=get-account-settings; Summary=Get account settings; Deprecated=}.OperationId) | Get account settings |
| PUT | $(@{Tag=Account settings; Method=PUT; Path=/v4/account/settings; OperationId=put-account-settings; Summary=Update account settings; Deprecated=}.Path) | $(@{Tag=Account settings; Method=PUT; Path=/v4/account/settings; OperationId=put-account-settings; Summary=Update account settings; Deprecated=}.OperationId) | Update account settings |
| POST | $(@{Tag=Account settings; Method=POST; Path=/v4/account/settings/managed-enable; OperationId=post-enable-account-managed; Summary=Enable Linode Managed; Deprecated=}.Path) | $(@{Tag=Account settings; Method=POST; Path=/v4/account/settings/managed-enable; OperationId=post-enable-account-managed; Summary=Enable Linode Managed; Deprecated=}.OperationId) | Enable Linode Managed |

## Account transfer

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Account transfer; Method=GET; Path=/v4/account/transfer; OperationId=get-transfer; Summary=Get network usage; Deprecated=}.Path) | $(@{Tag=Account transfer; Method=GET; Path=/v4/account/transfer; OperationId=get-transfer; Summary=Get network usage; Deprecated=}.OperationId) | Get network usage |

## Alerts

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-channels; OperationId=get-notification-channels; Summary=List notification channels; Deprecated=}.Path) | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-channels; OperationId=get-notification-channels; Summary=List notification channels; Deprecated=}.OperationId) | List notification channels |
| POST | $(@{Tag=Alerts; Method=POST; Path=/v4/monitor/alert-channels; OperationId=post-notification-channel; Summary=Create a notification channel; Deprecated=}.Path) | $(@{Tag=Alerts; Method=POST; Path=/v4/monitor/alert-channels; OperationId=post-notification-channel; Summary=Create a notification channel; Deprecated=}.OperationId) | Create a notification channel |
| DELETE | $(@{Tag=Alerts; Method=DELETE; Path=/v4/monitor/alert-channels/{channelId}; OperationId=delete-notification-channel; Summary=Delete a notification channel; Deprecated=}.Path) | $(@{Tag=Alerts; Method=DELETE; Path=/v4/monitor/alert-channels/{channelId}; OperationId=delete-notification-channel; Summary=Delete a notification channel; Deprecated=}.OperationId) | Delete a notification channel |
| GET | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-channels/{channelId}; OperationId=get-notification-channel; Summary=Get a notification channel; Deprecated=}.Path) | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-channels/{channelId}; OperationId=get-notification-channel; Summary=Get a notification channel; Deprecated=}.OperationId) | Get a notification channel |
| PUT | $(@{Tag=Alerts; Method=PUT; Path=/v4/monitor/alert-channels/{channelId}; OperationId=put-notification-channel; Summary=Update a notification channel; Deprecated=}.Path) | $(@{Tag=Alerts; Method=PUT; Path=/v4/monitor/alert-channels/{channelId}; OperationId=put-notification-channel; Summary=Update a notification channel; Deprecated=}.OperationId) | Update a notification channel |
| GET | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-channels/{channelId}/alerts; OperationId=get-notification-channel-alerts; Summary=List alerts for a notification channel; Deprecated=}.Path) | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-channels/{channelId}/alerts; OperationId=get-notification-channel-alerts; Summary=List alerts for a notification channel; Deprecated=}.OperationId) | List alerts for a notification channel |
| GET | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-definitions; OperationId=get-alert-definitions; Summary=List alert definitions; Deprecated=}.Path) | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/alert-definitions; OperationId=get-alert-definitions; Summary=List alert definitions; Deprecated=}.OperationId) | List alert definitions |
| GET | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/services/{serviceType}/alert-definitions; OperationId=get-alert-definitions-for-service-type; Summary=List alert definitions for a service type; Deprecated=}.Path) | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/services/{serviceType}/alert-definitions; OperationId=get-alert-definitions-for-service-type; Summary=List alert definitions for a service type; Deprecated=}.OperationId) | List alert definitions for a service type |
| POST | $(@{Tag=Alerts; Method=POST; Path=/v4/monitor/services/{serviceType}/alert-definitions; OperationId=post-alert-definition-for-service-type; Summary=Create an alert definition; Deprecated=}.Path) | $(@{Tag=Alerts; Method=POST; Path=/v4/monitor/services/{serviceType}/alert-definitions; OperationId=post-alert-definition-for-service-type; Summary=Create an alert definition; Deprecated=}.OperationId) | Create an alert definition |
| DELETE | $(@{Tag=Alerts; Method=DELETE; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}; OperationId=delete-alert-definition; Summary=Delete an alert definition; Deprecated=}.Path) | $(@{Tag=Alerts; Method=DELETE; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}; OperationId=delete-alert-definition; Summary=Delete an alert definition; Deprecated=}.OperationId) | Delete an alert definition |
| GET | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}; OperationId=get-alert-definition; Summary=Get an alert definition; Deprecated=}.Path) | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}; OperationId=get-alert-definition; Summary=Get an alert definition; Deprecated=}.OperationId) | Get an alert definition |
| PUT | $(@{Tag=Alerts; Method=PUT; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}; OperationId=put-alert-definition; Summary=Update an alert definition; Deprecated=}.Path) | $(@{Tag=Alerts; Method=PUT; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}; OperationId=put-alert-definition; Summary=Update an alert definition; Deprecated=}.OperationId) | Update an alert definition |
| GET | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}/entities; OperationId=get-alert-definition-entities; Summary=List entities assigned to an alert definition; Deprecated=}.Path) | $(@{Tag=Alerts; Method=GET; Path=/v4/monitor/services/{serviceType}/alert-definitions/{alertId}/entities; OperationId=get-alert-definition-entities; Summary=List entities assigned to an alert definition; Deprecated=}.OperationId) | List entities assigned to an alert definition |

## Attachments

| Method | Path | operationId | Summary |
|---|---|---|---|
| POST | $(@{Tag=Attachments; Method=POST; Path=/v4/support/tickets/{ticketId}/attachments; OperationId=post-ticket-attachment; Summary=Create a support ticket attachment; Deprecated=}.Path) | $(@{Tag=Attachments; Method=POST; Path=/v4/support/tickets/{ticketId}/attachments; OperationId=post-ticket-attachment; Summary=Create a support ticket attachment; Deprecated=}.OperationId) | Create a support ticket attachment |

## Buckets

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets; OperationId=get-object-storage-buckets; Summary=List Object Storage buckets; Deprecated=}.Path) | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets; OperationId=get-object-storage-buckets; Summary=List Object Storage buckets; Deprecated=}.OperationId) | List Object Storage buckets |
| POST | $(@{Tag=Buckets; Method=POST; Path=/v4/object-storage/buckets; OperationId=post-object-storage-bucket; Summary=Create an Object Storage bucket; Deprecated=}.Path) | $(@{Tag=Buckets; Method=POST; Path=/v4/object-storage/buckets; OperationId=post-object-storage-bucket; Summary=Create an Object Storage bucket; Deprecated=}.OperationId) | Create an Object Storage bucket |
| GET | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}; OperationId=get-object-storage-bucketin-cluster; Summary=List Object Storage buckets per region; Deprecated=}.Path) | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}; OperationId=get-object-storage-bucketin-cluster; Summary=List Object Storage buckets per region; Deprecated=}.OperationId) | List Object Storage buckets per region |
| DELETE | $(@{Tag=Buckets; Method=DELETE; Path=/v4/object-storage/buckets/{regionId}/{bucket}; OperationId=delete-object-storage-bucket; Summary=Remove an Object Storage bucket; Deprecated=}.Path) | $(@{Tag=Buckets; Method=DELETE; Path=/v4/object-storage/buckets/{regionId}/{bucket}; OperationId=delete-object-storage-bucket; Summary=Remove an Object Storage bucket; Deprecated=}.OperationId) | Remove an Object Storage bucket |
| GET | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}; OperationId=get-object-storage-bucket; Summary=Get an Object Storage bucket; Deprecated=}.Path) | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}; OperationId=get-object-storage-bucket; Summary=Get an Object Storage bucket; Deprecated=}.OperationId) | Get an Object Storage bucket |
| GET | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/access; OperationId=get-object-storage-bucket-access; Summary=Get Object Storage bucket access; Deprecated=}.Path) | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/access; OperationId=get-object-storage-bucket-access; Summary=Get Object Storage bucket access; Deprecated=}.OperationId) | Get Object Storage bucket access |
| POST | $(@{Tag=Buckets; Method=POST; Path=/v4/object-storage/buckets/{regionId}/{bucket}/access; OperationId=post-object-storage-bucket-access; Summary=Allow access to an Object Storage bucket; Deprecated=}.Path) | $(@{Tag=Buckets; Method=POST; Path=/v4/object-storage/buckets/{regionId}/{bucket}/access; OperationId=post-object-storage-bucket-access; Summary=Allow access to an Object Storage bucket; Deprecated=}.OperationId) | Allow access to an Object Storage bucket |
| PUT | $(@{Tag=Buckets; Method=PUT; Path=/v4/object-storage/buckets/{regionId}/{bucket}/access; OperationId=put-storage-bucket-access; Summary=Update access to an Object Storage bucket; Deprecated=}.Path) | $(@{Tag=Buckets; Method=PUT; Path=/v4/object-storage/buckets/{regionId}/{bucket}/access; OperationId=put-storage-bucket-access; Summary=Update access to an Object Storage bucket; Deprecated=}.OperationId) | Update access to an Object Storage bucket |
| GET | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-acl; OperationId=get-object-storage-bucket-acl; Summary=Get an Object Storage object ACL configuration; Deprecated=}.Path) | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-acl; OperationId=get-object-storage-bucket-acl; Summary=Get an Object Storage object ACL configuration; Deprecated=}.OperationId) | Get an Object Storage object ACL configuration |
| PUT | $(@{Tag=Buckets; Method=PUT; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-acl; OperationId=put-object-storage-bucket-acl; Summary=Update an object's ACL configuration; Deprecated=}.Path) | $(@{Tag=Buckets; Method=PUT; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-acl; OperationId=put-object-storage-bucket-acl; Summary=Update an object's ACL configuration; Deprecated=}.OperationId) | Update an object's ACL configuration |
| GET | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-list; OperationId=get-object-storage-bucket-content; Summary=List Object Storage bucket contents; Deprecated=}.Path) | $(@{Tag=Buckets; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-list; OperationId=get-object-storage-bucket-content; Summary=List Object Storage bucket contents; Deprecated=}.OperationId) | List Object Storage bucket contents |
| POST | $(@{Tag=Buckets; Method=POST; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-url; OperationId=post-object-storage-object-url; Summary=Create a URL for an object; Deprecated=}.Path) | $(@{Tag=Buckets; Method=POST; Path=/v4/object-storage/buckets/{regionId}/{bucket}/object-url; OperationId=post-object-storage-object-url; Summary=Create a URL for an object; Deprecated=}.OperationId) | Create a URL for an object |

## Child accounts

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Child accounts; Method=GET; Path=/v4/account/child-accounts; OperationId=get-child-accounts; Summary=List child accounts (Deprecated); Deprecated=yes}.Path) | $(@{Tag=Child accounts; Method=GET; Path=/v4/account/child-accounts; OperationId=get-child-accounts; Summary=List child accounts (Deprecated); Deprecated=yes}.OperationId) | [DEPRECATED] List child accounts (Deprecated) |
| GET | $(@{Tag=Child accounts; Method=GET; Path=/v4/account/child-accounts/{euuId}; OperationId=get-child-account; Summary=Get a child account (Deprecated); Deprecated=yes}.Path) | $(@{Tag=Child accounts; Method=GET; Path=/v4/account/child-accounts/{euuId}; OperationId=get-child-account; Summary=Get a child account (Deprecated); Deprecated=yes}.OperationId) | [DEPRECATED] Get a child account (Deprecated) |
| POST | $(@{Tag=Child accounts; Method=POST; Path=/v4/account/child-accounts/{euuId}/token; OperationId=post-child-account-token; Summary=Create a proxy user token; Deprecated=yes}.Path) | $(@{Tag=Child accounts; Method=POST; Path=/v4/account/child-accounts/{euuId}/token; OperationId=post-child-account-token; Summary=Create a proxy user token; Deprecated=yes}.OperationId) | [DEPRECATED] Create a proxy user token |

## Configuration profile interfaces

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Configuration profile interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces; OperationId=get-linode-config-interfaces; Summary=List configuration profile interfaces; Deprecated=}.Path) | $(@{Tag=Configuration profile interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces; OperationId=get-linode-config-interfaces; Summary=List configuration profile interfaces; Deprecated=}.OperationId) | List configuration profile interfaces |
| POST | $(@{Tag=Configuration profile interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces; OperationId=post-linode-config-interface; Summary=Add a configuration profile interface; Deprecated=}.Path) | $(@{Tag=Configuration profile interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces; OperationId=post-linode-config-interface; Summary=Add a configuration profile interface; Deprecated=}.OperationId) | Add a configuration profile interface |
| DELETE | $(@{Tag=Configuration profile interfaces; Method=DELETE; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/{interfaceId}; OperationId=delete-linode-config-interface; Summary=Delete a configuration profile interface; Deprecated=}.Path) | $(@{Tag=Configuration profile interfaces; Method=DELETE; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/{interfaceId}; OperationId=delete-linode-config-interface; Summary=Delete a configuration profile interface; Deprecated=}.OperationId) | Delete a configuration profile interface |
| GET | $(@{Tag=Configuration profile interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/{interfaceId}; OperationId=get-linode-config-interface; Summary=Get a configuration profile interface; Deprecated=}.Path) | $(@{Tag=Configuration profile interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/{interfaceId}; OperationId=get-linode-config-interface; Summary=Get a configuration profile interface; Deprecated=}.OperationId) | Get a configuration profile interface |
| PUT | $(@{Tag=Configuration profile interfaces; Method=PUT; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/{interfaceId}; OperationId=put-linode-config-interface; Summary=Update a configuration profile interface; Deprecated=}.Path) | $(@{Tag=Configuration profile interfaces; Method=PUT; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/{interfaceId}; OperationId=put-linode-config-interface; Summary=Update a configuration profile interface; Deprecated=}.OperationId) | Update a configuration profile interface |
| POST | $(@{Tag=Configuration profile interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/order; OperationId=post-linode-config-interfaces; Summary=Reorder configuration profile interfaces; Deprecated=}.Path) | $(@{Tag=Configuration profile interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/configs/{configId}/interfaces/order; OperationId=post-linode-config-interfaces; Summary=Reorder configuration profile interfaces; Deprecated=}.OperationId) | Reorder configuration profile interfaces |

## Configuration profiles

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Configuration profiles; Method=GET; Path=/v4/linode/instances/{linodeId}/configs; OperationId=get-linode-configs; Summary=List configuration profiles; Deprecated=}.Path) | $(@{Tag=Configuration profiles; Method=GET; Path=/v4/linode/instances/{linodeId}/configs; OperationId=get-linode-configs; Summary=List configuration profiles; Deprecated=}.OperationId) | List configuration profiles |
| POST | $(@{Tag=Configuration profiles; Method=POST; Path=/v4/linode/instances/{linodeId}/configs; OperationId=post-add-linode-config; Summary=Create a configuration profile; Deprecated=}.Path) | $(@{Tag=Configuration profiles; Method=POST; Path=/v4/linode/instances/{linodeId}/configs; OperationId=post-add-linode-config; Summary=Create a configuration profile; Deprecated=}.OperationId) | Create a configuration profile |
| DELETE | $(@{Tag=Configuration profiles; Method=DELETE; Path=/v4/linode/instances/{linodeId}/configs/{configId}; OperationId=delete-linode-config; Summary=Delete a configuration profile; Deprecated=}.Path) | $(@{Tag=Configuration profiles; Method=DELETE; Path=/v4/linode/instances/{linodeId}/configs/{configId}; OperationId=delete-linode-config; Summary=Delete a configuration profile; Deprecated=}.OperationId) | Delete a configuration profile |
| GET | $(@{Tag=Configuration profiles; Method=GET; Path=/v4/linode/instances/{linodeId}/configs/{configId}; OperationId=get-linode-config; Summary=Get a configuration profile; Deprecated=}.Path) | $(@{Tag=Configuration profiles; Method=GET; Path=/v4/linode/instances/{linodeId}/configs/{configId}; OperationId=get-linode-config; Summary=Get a configuration profile; Deprecated=}.OperationId) | Get a configuration profile |
| PUT | $(@{Tag=Configuration profiles; Method=PUT; Path=/v4/linode/instances/{linodeId}/configs/{configId}; OperationId=put-linode-config; Summary=Update a configuration profile; Deprecated=}.Path) | $(@{Tag=Configuration profiles; Method=PUT; Path=/v4/linode/instances/{linodeId}/configs/{configId}; OperationId=put-linode-config; Summary=Update a configuration profile; Deprecated=}.OperationId) | Update a configuration profile |

## Configurations

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Configurations; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs; OperationId=get-node-balancer-configs; Summary=List configs; Deprecated=}.Path) | $(@{Tag=Configurations; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs; OperationId=get-node-balancer-configs; Summary=List configs; Deprecated=}.OperationId) | List configs |
| POST | $(@{Tag=Configurations; Method=POST; Path=/v4/nodebalancers/{nodeBalancerId}/configs; OperationId=post-node-balancer-config; Summary=Create a config; Deprecated=}.Path) | $(@{Tag=Configurations; Method=POST; Path=/v4/nodebalancers/{nodeBalancerId}/configs; OperationId=post-node-balancer-config; Summary=Create a config; Deprecated=}.OperationId) | Create a config |
| DELETE | $(@{Tag=Configurations; Method=DELETE; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}; OperationId=delete-node-balancer-config; Summary=Delete a config; Deprecated=}.Path) | $(@{Tag=Configurations; Method=DELETE; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}; OperationId=delete-node-balancer-config; Summary=Delete a config; Deprecated=}.OperationId) | Delete a config |
| GET | $(@{Tag=Configurations; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}; OperationId=get-node-balancer-config; Summary=Get a config; Deprecated=}.Path) | $(@{Tag=Configurations; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}; OperationId=get-node-balancer-config; Summary=Get a config; Deprecated=}.OperationId) | Get a config |
| PUT | $(@{Tag=Configurations; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}; OperationId=put-node-balancer-config; Summary=Update a config; Deprecated=}.Path) | $(@{Tag=Configurations; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}; OperationId=put-node-balancer-config; Summary=Update a config; Deprecated=}.OperationId) | Update a config |
| POST | $(@{Tag=Configurations; Method=POST; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/rebuild; OperationId=post-rebuild-node-balancer-config; Summary=Rebuild a config; Deprecated=}.Path) | $(@{Tag=Configurations; Method=POST; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/rebuild; OperationId=post-rebuild-node-balancer-config; Summary=Rebuild a config; Deprecated=}.OperationId) | Rebuild a config |

## Control Plane ACL

| Method | Path | operationId | Summary |
|---|---|---|---|
| DELETE | $(@{Tag=Control Plane ACL; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/control_plane_acl; OperationId=delete-lke-cluster-acl; Summary=Delete the control plane access control list; Deprecated=}.Path) | $(@{Tag=Control Plane ACL; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/control_plane_acl; OperationId=delete-lke-cluster-acl; Summary=Delete the control plane access control list; Deprecated=}.OperationId) | Delete the control plane access control list |
| GET | $(@{Tag=Control Plane ACL; Method=GET; Path=/v4/lke/clusters/{clusterId}/control_plane_acl; OperationId=get-lke-cluster-acl; Summary=Get the control plane access control list; Deprecated=}.Path) | $(@{Tag=Control Plane ACL; Method=GET; Path=/v4/lke/clusters/{clusterId}/control_plane_acl; OperationId=get-lke-cluster-acl; Summary=Get the control plane access control list; Deprecated=}.OperationId) | Get the control plane access control list |
| PUT | $(@{Tag=Control Plane ACL; Method=PUT; Path=/v4/lke/clusters/{clusterId}/control_plane_acl; OperationId=put-lke-cluster-acl; Summary=Update the control plane access control list; Deprecated=}.Path) | $(@{Tag=Control Plane ACL; Method=PUT; Path=/v4/lke/clusters/{clusterId}/control_plane_acl; OperationId=put-lke-cluster-acl; Summary=Update the control plane access control list; Deprecated=}.OperationId) | Update the control plane access control list |

## Delegation for parent and child accounts

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/child-accounts; OperationId=get-iam-delegation-all-child-accounts; Summary=List child accounts; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/child-accounts; OperationId=get-iam-delegation-all-child-accounts; Summary=List child accounts; Deprecated=}.OperationId) | List child accounts |
| GET | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/child-accounts/{euuid}/users; OperationId=get-iam-delegation-child-account-users; Summary=Get the account delegation for a child account; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/child-accounts/{euuid}/users; OperationId=get-iam-delegation-child-account-users; Summary=Get the account delegation for a child account; Deprecated=}.OperationId) | Get the account delegation for a child account |
| PUT | $(@{Tag=Delegation for parent and child accounts; Method=PUT; Path=/v4/iam/delegation/child-accounts/{euuid}/users; OperationId=put-iam-delegation-child-account-users; Summary=Update the account delegation for a child account; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=PUT; Path=/v4/iam/delegation/child-accounts/{euuid}/users; OperationId=put-iam-delegation-child-account-users; Summary=Update the account delegation for a child account; Deprecated=}.OperationId) | Update the account delegation for a child account |
| GET | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/default-role-permissions; OperationId=get-iam-delegation-default-role-permissions; Summary=Get the default role assignment for delegate users; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/default-role-permissions; OperationId=get-iam-delegation-default-role-permissions; Summary=Get the default role assignment for delegate users; Deprecated=}.OperationId) | Get the default role assignment for delegate users |
| PUT | $(@{Tag=Delegation for parent and child accounts; Method=PUT; Path=/v4/iam/delegation/default-role-permissions; OperationId=put-iam-delegation-default-role-permissions; Summary=Update the default role assignment for delegate users; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=PUT; Path=/v4/iam/delegation/default-role-permissions; OperationId=put-iam-delegation-default-role-permissions; Summary=Update the default role assignment for delegate users; Deprecated=}.OperationId) | Update the default role assignment for delegate users |
| GET | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/profile/child-accounts; OperationId=get-iam-delegation-profile-child-account; Summary=Get your account delegations; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/profile/child-accounts; OperationId=get-iam-delegation-profile-child-account; Summary=Get your account delegations; Deprecated=}.OperationId) | Get your account delegations |
| GET | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/profile/child-accounts/{euuid}; OperationId=get-delegation-profile-child-account; Summary=Get a child account; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/profile/child-accounts/{euuid}; OperationId=get-delegation-profile-child-account; Summary=Get a child account; Deprecated=}.OperationId) | Get a child account |
| POST | $(@{Tag=Delegation for parent and child accounts; Method=POST; Path=/v4/iam/delegation/profile/child-accounts/{euuid}/token; OperationId=post-iam-delegation-profile-child-account-token; Summary=Create a delegate user token; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=POST; Path=/v4/iam/delegation/profile/child-accounts/{euuid}/token; OperationId=post-iam-delegation-profile-child-account-token; Summary=Create a delegate user token; Deprecated=}.OperationId) | Create a delegate user token |
| GET | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/users/{username}/child-accounts; OperationId=get-iam-delegation-user-child-accounts; Summary=Get user's account delegations; Deprecated=}.Path) | $(@{Tag=Delegation for parent and child accounts; Method=GET; Path=/v4/iam/delegation/users/{username}/child-accounts; OperationId=get-iam-delegation-user-child-accounts; Summary=Get user's account delegations; Deprecated=}.OperationId) | Get user's account delegations |

## Devices

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Devices; Method=GET; Path=/v4/networking/firewalls/{firewallId}/devices; OperationId=get-firewall-devices; Summary=List firewall devices; Deprecated=}.Path) | $(@{Tag=Devices; Method=GET; Path=/v4/networking/firewalls/{firewallId}/devices; OperationId=get-firewall-devices; Summary=List firewall devices; Deprecated=}.OperationId) | List firewall devices |
| POST | $(@{Tag=Devices; Method=POST; Path=/v4/networking/firewalls/{firewallId}/devices; OperationId=post-firewall-device; Summary=Create a firewall device; Deprecated=}.Path) | $(@{Tag=Devices; Method=POST; Path=/v4/networking/firewalls/{firewallId}/devices; OperationId=post-firewall-device; Summary=Create a firewall device; Deprecated=}.OperationId) | Create a firewall device |
| DELETE | $(@{Tag=Devices; Method=DELETE; Path=/v4/networking/firewalls/{firewallId}/devices/{deviceId}; OperationId=delete-firewall-device; Summary=Delete a firewall device; Deprecated=}.Path) | $(@{Tag=Devices; Method=DELETE; Path=/v4/networking/firewalls/{firewallId}/devices/{deviceId}; OperationId=delete-firewall-device; Summary=Delete a firewall device; Deprecated=}.OperationId) | Delete a firewall device |
| GET | $(@{Tag=Devices; Method=GET; Path=/v4/networking/firewalls/{firewallId}/devices/{deviceId}; OperationId=get-firewall-device; Summary=Get a firewall device; Deprecated=}.Path) | $(@{Tag=Devices; Method=GET; Path=/v4/networking/firewalls/{firewallId}/devices/{deviceId}; OperationId=get-firewall-device; Summary=Get a firewall device; Deprecated=}.OperationId) | Get a firewall device |

## Domain records

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Domain records; Method=GET; Path=/v4/domains/{domainId}/records; OperationId=get-domain-records; Summary=List domain records; Deprecated=}.Path) | $(@{Tag=Domain records; Method=GET; Path=/v4/domains/{domainId}/records; OperationId=get-domain-records; Summary=List domain records; Deprecated=}.OperationId) | List domain records |
| POST | $(@{Tag=Domain records; Method=POST; Path=/v4/domains/{domainId}/records; OperationId=post-domain-record; Summary=Create a domain record; Deprecated=}.Path) | $(@{Tag=Domain records; Method=POST; Path=/v4/domains/{domainId}/records; OperationId=post-domain-record; Summary=Create a domain record; Deprecated=}.OperationId) | Create a domain record |
| DELETE | $(@{Tag=Domain records; Method=DELETE; Path=/v4/domains/{domainId}/records/{recordId}; OperationId=delete-domain-record; Summary=Delete a domain record; Deprecated=}.Path) | $(@{Tag=Domain records; Method=DELETE; Path=/v4/domains/{domainId}/records/{recordId}; OperationId=delete-domain-record; Summary=Delete a domain record; Deprecated=}.OperationId) | Delete a domain record |
| GET | $(@{Tag=Domain records; Method=GET; Path=/v4/domains/{domainId}/records/{recordId}; OperationId=get-domain-record; Summary=Get a domain record; Deprecated=}.Path) | $(@{Tag=Domain records; Method=GET; Path=/v4/domains/{domainId}/records/{recordId}; OperationId=get-domain-record; Summary=Get a domain record; Deprecated=}.OperationId) | Get a domain record |
| PUT | $(@{Tag=Domain records; Method=PUT; Path=/v4/domains/{domainId}/records/{recordId}; OperationId=put-domain-record; Summary=Update a domain record; Deprecated=}.Path) | $(@{Tag=Domain records; Method=PUT; Path=/v4/domains/{domainId}/records/{recordId}; OperationId=put-domain-record; Summary=Update a domain record; Deprecated=}.OperationId) | Update a domain record |

## Domain zone files

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Domain zone files; Method=GET; Path=/v4/domains/{domainId}/zone-file; OperationId=get-domain-zone; Summary=Get a domain zone file; Deprecated=}.Path) | $(@{Tag=Domain zone files; Method=GET; Path=/v4/domains/{domainId}/zone-file; OperationId=get-domain-zone; Summary=Get a domain zone file; Deprecated=}.OperationId) | Get a domain zone file |

## Domains

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Domains; Method=GET; Path=/v4/domains; OperationId=get-domains; Summary=List domains; Deprecated=}.Path) | $(@{Tag=Domains; Method=GET; Path=/v4/domains; OperationId=get-domains; Summary=List domains; Deprecated=}.OperationId) | List domains |
| POST | $(@{Tag=Domains; Method=POST; Path=/v4/domains; OperationId=post-domain; Summary=Create a domain; Deprecated=}.Path) | $(@{Tag=Domains; Method=POST; Path=/v4/domains; OperationId=post-domain; Summary=Create a domain; Deprecated=}.OperationId) | Create a domain |
| DELETE | $(@{Tag=Domains; Method=DELETE; Path=/v4/domains/{domainId}; OperationId=delete-domain; Summary=Delete a domain; Deprecated=}.Path) | $(@{Tag=Domains; Method=DELETE; Path=/v4/domains/{domainId}; OperationId=delete-domain; Summary=Delete a domain; Deprecated=}.OperationId) | Delete a domain |
| GET | $(@{Tag=Domains; Method=GET; Path=/v4/domains/{domainId}; OperationId=get-domain; Summary=Get a domain; Deprecated=}.Path) | $(@{Tag=Domains; Method=GET; Path=/v4/domains/{domainId}; OperationId=get-domain; Summary=Get a domain; Deprecated=}.OperationId) | Get a domain |
| PUT | $(@{Tag=Domains; Method=PUT; Path=/v4/domains/{domainId}; OperationId=put-domain; Summary=Update a domain; Deprecated=}.Path) | $(@{Tag=Domains; Method=PUT; Path=/v4/domains/{domainId}; OperationId=put-domain; Summary=Update a domain; Deprecated=}.OperationId) | Update a domain |
| POST | $(@{Tag=Domains; Method=POST; Path=/v4/domains/{domainId}/clone; OperationId=post-clone-domain; Summary=Clone a domain; Deprecated=}.Path) | $(@{Tag=Domains; Method=POST; Path=/v4/domains/{domainId}/clone; OperationId=post-clone-domain; Summary=Clone a domain; Deprecated=}.OperationId) | Clone a domain |
| POST | $(@{Tag=Domains; Method=POST; Path=/v4/domains/import; OperationId=post-import-domain; Summary=Import a domain; Deprecated=}.Path) | $(@{Tag=Domains; Method=POST; Path=/v4/domains/import; OperationId=post-import-domain; Summary=Import a domain; Deprecated=}.OperationId) | Import a domain |

## Endpoints

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Endpoints; Method=GET; Path=/v4/object-storage/endpoints; OperationId=get-object-storage-endpoints; Summary=List Object Storage endpoints; Deprecated=}.Path) | $(@{Tag=Endpoints; Method=GET; Path=/v4/object-storage/endpoints; OperationId=get-object-storage-endpoints; Summary=List Object Storage endpoints; Deprecated=}.OperationId) | List Object Storage endpoints |

## Entity transfers

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Entity transfers; Method=GET; Path=/v4/account/entity-transfers; OperationId=get-entity-transfers; Summary=List entity transfers; Deprecated=yes}.Path) | $(@{Tag=Entity transfers; Method=GET; Path=/v4/account/entity-transfers; OperationId=get-entity-transfers; Summary=List entity transfers; Deprecated=yes}.OperationId) | [DEPRECATED] List entity transfers |
| POST | $(@{Tag=Entity transfers; Method=POST; Path=/v4/account/entity-transfers; OperationId=post-entity-transfer; Summary=Create an entity transfer; Deprecated=yes}.Path) | $(@{Tag=Entity transfers; Method=POST; Path=/v4/account/entity-transfers; OperationId=post-entity-transfer; Summary=Create an entity transfer; Deprecated=yes}.OperationId) | [DEPRECATED] Create an entity transfer |
| DELETE | $(@{Tag=Entity transfers; Method=DELETE; Path=/v4/account/entity-transfers/{token}; OperationId=delete-entity-transfer; Summary=Cancel an entity transfer; Deprecated=yes}.Path) | $(@{Tag=Entity transfers; Method=DELETE; Path=/v4/account/entity-transfers/{token}; OperationId=delete-entity-transfer; Summary=Cancel an entity transfer; Deprecated=yes}.OperationId) | [DEPRECATED] Cancel an entity transfer |
| GET | $(@{Tag=Entity transfers; Method=GET; Path=/v4/account/entity-transfers/{token}; OperationId=get-entity-transfer; Summary=Get an entity transfer; Deprecated=yes}.Path) | $(@{Tag=Entity transfers; Method=GET; Path=/v4/account/entity-transfers/{token}; OperationId=get-entity-transfer; Summary=Get an entity transfer; Deprecated=yes}.OperationId) | [DEPRECATED] Get an entity transfer |
| POST | $(@{Tag=Entity transfers; Method=POST; Path=/v4/account/entity-transfers/{token}/accept; OperationId=post-accept-entity-transfer; Summary=Accept an entity transfer; Deprecated=yes}.Path) | $(@{Tag=Entity transfers; Method=POST; Path=/v4/account/entity-transfers/{token}/accept; OperationId=post-accept-entity-transfer; Summary=Accept an entity transfer; Deprecated=yes}.OperationId) | [DEPRECATED] Accept an entity transfer |

## Events

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Events; Method=GET; Path=/v4/account/events; OperationId=get-events; Summary=List events; Deprecated=}.Path) | $(@{Tag=Events; Method=GET; Path=/v4/account/events; OperationId=get-events; Summary=List events; Deprecated=}.OperationId) | List events |
| GET | $(@{Tag=Events; Method=GET; Path=/v4/account/events/{eventId}; OperationId=get-event; Summary=Get an event; Deprecated=}.Path) | $(@{Tag=Events; Method=GET; Path=/v4/account/events/{eventId}; OperationId=get-event; Summary=Get an event; Deprecated=}.OperationId) | Get an event |
| POST | $(@{Tag=Events; Method=POST; Path=/v4/account/events/{eventId}/seen; OperationId=post-event-seen; Summary=Mark an event as seen; Deprecated=}.Path) | $(@{Tag=Events; Method=POST; Path=/v4/account/events/{eventId}/seen; OperationId=post-event-seen; Summary=Mark an event as seen; Deprecated=}.OperationId) | Mark an event as seen |

## Firewall settings

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Firewall settings; Method=GET; Path=/v4/networking/firewalls/settings; OperationId=get-firewall-settings; Summary=List default firewalls; Deprecated=}.Path) | $(@{Tag=Firewall settings; Method=GET; Path=/v4/networking/firewalls/settings; OperationId=get-firewall-settings; Summary=List default firewalls; Deprecated=}.OperationId) | List default firewalls |
| PUT | $(@{Tag=Firewall settings; Method=PUT; Path=/v4/networking/firewalls/settings; OperationId=put-firewall-settings; Summary=Update default firewalls; Deprecated=}.Path) | $(@{Tag=Firewall settings; Method=PUT; Path=/v4/networking/firewalls/settings; OperationId=put-firewall-settings; Summary=Update default firewalls; Deprecated=}.OperationId) | Update default firewalls |

## Firewalls

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls; OperationId=get-firewalls; Summary=List firewalls; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls; OperationId=get-firewalls; Summary=List firewalls; Deprecated=}.OperationId) | List firewalls |
| POST | $(@{Tag=Firewalls; Method=POST; Path=/v4/networking/firewalls; OperationId=post-firewalls; Summary=Create a firewall; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=POST; Path=/v4/networking/firewalls; OperationId=post-firewalls; Summary=Create a firewall; Deprecated=}.OperationId) | Create a firewall |
| DELETE | $(@{Tag=Firewalls; Method=DELETE; Path=/v4/networking/firewalls/{firewallId}; OperationId=delete-firewall; Summary=Delete a firewall; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=DELETE; Path=/v4/networking/firewalls/{firewallId}; OperationId=delete-firewall; Summary=Delete a firewall; Deprecated=}.OperationId) | Delete a firewall |
| GET | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}; OperationId=get-firewall; Summary=Get a firewall; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}; OperationId=get-firewall; Summary=Get a firewall; Deprecated=}.OperationId) | Get a firewall |
| PUT | $(@{Tag=Firewalls; Method=PUT; Path=/v4/networking/firewalls/{firewallId}; OperationId=put-firewall; Summary=Update a firewall; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=PUT; Path=/v4/networking/firewalls/{firewallId}; OperationId=put-firewall; Summary=Update a firewall; Deprecated=}.OperationId) | Update a firewall |
| GET | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}/history; OperationId=get-firewall-rule-versions; Summary=List firewall rule versions; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}/history; OperationId=get-firewall-rule-versions; Summary=List firewall rule versions; Deprecated=}.OperationId) | List firewall rule versions |
| GET | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}/history/rules/{version}; OperationId=get-firewall-rule-version; Summary=Get a firewall rule version; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}/history/rules/{version}; OperationId=get-firewall-rule-version; Summary=Get a firewall rule version; Deprecated=}.OperationId) | Get a firewall rule version |
| GET | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}/rules; OperationId=get-firewall-rules; Summary=List firewall rules; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=GET; Path=/v4/networking/firewalls/{firewallId}/rules; OperationId=get-firewall-rules; Summary=List firewall rules; Deprecated=}.OperationId) | List firewall rules |
| PUT | $(@{Tag=Firewalls; Method=PUT; Path=/v4/networking/firewalls/{firewallId}/rules; OperationId=put-firewall-rules; Summary=Update firewall rules; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=PUT; Path=/v4/networking/firewalls/{firewallId}/rules; OperationId=put-firewall-rules; Summary=Update firewall rules; Deprecated=}.OperationId) | Update firewall rules |
| GET | $(@{Tag=Firewalls; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/firewalls; OperationId=get-node-balancer-firewalls; Summary=List NodeBalancer firewalls; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/firewalls; OperationId=get-node-balancer-firewalls; Summary=List NodeBalancer firewalls; Deprecated=}.OperationId) | List NodeBalancer firewalls |
| PUT | $(@{Tag=Firewalls; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}/firewalls; OperationId=put-node-balancer-firewalls; Summary=Update a NodeBalancer's firewalls; Deprecated=}.Path) | $(@{Tag=Firewalls; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}/firewalls; OperationId=put-node-balancer-firewalls; Summary=Update a NodeBalancer's firewalls; Deprecated=}.OperationId) | Update a NodeBalancer's firewalls |

## General

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=General; Method=GET; Path=/v4/databases/engines; OperationId=get-databases-engines; Summary=List Managed Databases engines; Deprecated=}.Path) | $(@{Tag=General; Method=GET; Path=/v4/databases/engines; OperationId=get-databases-engines; Summary=List Managed Databases engines; Deprecated=}.OperationId) | List Managed Databases engines |
| GET | $(@{Tag=General; Method=GET; Path=/v4/databases/engines/{engineId}; OperationId=get-databases-engine; Summary=Get a Managed Databases engine; Deprecated=}.Path) | $(@{Tag=General; Method=GET; Path=/v4/databases/engines/{engineId}; OperationId=get-databases-engine; Summary=Get a Managed Databases engine; Deprecated=}.OperationId) | Get a Managed Databases engine |
| GET | $(@{Tag=General; Method=GET; Path=/v4/databases/instances; OperationId=get-databases-instances; Summary=List Managed Databases; Deprecated=}.Path) | $(@{Tag=General; Method=GET; Path=/v4/databases/instances; OperationId=get-databases-instances; Summary=List Managed Databases; Deprecated=}.OperationId) | List Managed Databases |
| GET | $(@{Tag=General; Method=GET; Path=/v4/databases/types; OperationId=get-databases-types; Summary=List Managed Databases types; Deprecated=}.Path) | $(@{Tag=General; Method=GET; Path=/v4/databases/types; OperationId=get-databases-types; Summary=List Managed Databases types; Deprecated=}.OperationId) | List Managed Databases types |
| GET | $(@{Tag=General; Method=GET; Path=/v4/databases/types/{typeId}; OperationId=get-databases-type; Summary=Get a Managed Databases type; Deprecated=}.Path) | $(@{Tag=General; Method=GET; Path=/v4/databases/types/{typeId}; OperationId=get-databases-type; Summary=Get a Managed Databases type; Deprecated=}.OperationId) | Get a Managed Databases type |

## Grants

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Grants; Method=GET; Path=/v4/profile/grants; OperationId=get-profile-grants; Summary=List grants; Deprecated=}.Path) | $(@{Tag=Grants; Method=GET; Path=/v4/profile/grants; OperationId=get-profile-grants; Summary=List grants; Deprecated=}.OperationId) | List grants |

## Identity Management

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Identity Management; Method=GET; Path=/v4/entities; OperationId=get-entities; Summary=List entities; Deprecated=}.Path) | $(@{Tag=Identity Management; Method=GET; Path=/v4/entities; OperationId=get-entities; Summary=List entities; Deprecated=}.OperationId) | List entities |
| GET | $(@{Tag=Identity Management; Method=GET; Path=/v4/iam/role-permissions; OperationId=get-role-permissions; Summary=List available roles; Deprecated=}.Path) | $(@{Tag=Identity Management; Method=GET; Path=/v4/iam/role-permissions; OperationId=get-role-permissions; Summary=List available roles; Deprecated=}.OperationId) | List available roles |
| GET | $(@{Tag=Identity Management; Method=GET; Path=/v4/iam/users/{username}/role-permissions; OperationId=get-iam-users-role-permissions; Summary=Get a user's access level; Deprecated=}.Path) | $(@{Tag=Identity Management; Method=GET; Path=/v4/iam/users/{username}/role-permissions; OperationId=get-iam-users-role-permissions; Summary=Get a user's access level; Deprecated=}.OperationId) | Get a user's access level |
| PUT | $(@{Tag=Identity Management; Method=PUT; Path=/v4/iam/users/{username}/role-permissions; OperationId=put-iam-users-role-permissions; Summary=Update a user's access level; Deprecated=}.Path) | $(@{Tag=Identity Management; Method=PUT; Path=/v4/iam/users/{username}/role-permissions; OperationId=put-iam-users-role-permissions; Summary=Update a user's access level; Deprecated=}.OperationId) | Update a user's access level |

## IDP configuration

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs; OperationId=get-idp-configs; Summary=List IDP configurations; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs; OperationId=get-idp-configs; Summary=List IDP configurations; Deprecated=}.OperationId) | List IDP configurations |
| POST | $(@{Tag=IDP configuration; Method=POST; Path=/v4/iam/idp-configs; OperationId=post-idp-config; Summary=Create an IDP configuration; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=POST; Path=/v4/iam/idp-configs; OperationId=post-idp-config; Summary=Create an IDP configuration; Deprecated=}.OperationId) | Create an IDP configuration |
| DELETE | $(@{Tag=IDP configuration; Method=DELETE; Path=/v4/iam/idp-configs/{idpConfigId}; OperationId=delete-idp-config; Summary=Delete an IDP configuration; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=DELETE; Path=/v4/iam/idp-configs/{idpConfigId}; OperationId=delete-idp-config; Summary=Delete an IDP configuration; Deprecated=}.OperationId) | Delete an IDP configuration |
| GET | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}; OperationId=get-idp-config; Summary=Get an IDP configuration; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}; OperationId=get-idp-config; Summary=Get an IDP configuration; Deprecated=}.OperationId) | Get an IDP configuration |
| PUT | $(@{Tag=IDP configuration; Method=PUT; Path=/v4/iam/idp-configs/{idpConfigId}; OperationId=put-idp-config; Summary=Update an IDP configuration; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=PUT; Path=/v4/iam/idp-configs/{idpConfigId}; OperationId=put-idp-config; Summary=Update an IDP configuration; Deprecated=}.OperationId) | Update an IDP configuration |
| GET | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}/certificates; OperationId=get-idp-config-certificates; Summary=List IDP certificates; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}/certificates; OperationId=get-idp-config-certificates; Summary=List IDP certificates; Deprecated=}.OperationId) | List IDP certificates |
| POST | $(@{Tag=IDP configuration; Method=POST; Path=/v4/iam/idp-configs/{idpConfigId}/certificates; OperationId=post-idp-config-certificate; Summary=Add an IDP certificate; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=POST; Path=/v4/iam/idp-configs/{idpConfigId}/certificates; OperationId=post-idp-config-certificate; Summary=Add an IDP certificate; Deprecated=}.OperationId) | Add an IDP certificate |
| DELETE | $(@{Tag=IDP configuration; Method=DELETE; Path=/v4/iam/idp-configs/{idpConfigId}/certificates/{certificateId}; OperationId=delete-idp-config-certificate; Summary=Delete an IDP certificate; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=DELETE; Path=/v4/iam/idp-configs/{idpConfigId}/certificates/{certificateId}; OperationId=delete-idp-config-certificate; Summary=Delete an IDP certificate; Deprecated=}.OperationId) | Delete an IDP certificate |
| GET | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}/users-excluded; OperationId=get-idp-config-users-excluded; Summary=List excluded users; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}/users-excluded; OperationId=get-idp-config-users-excluded; Summary=List excluded users; Deprecated=}.OperationId) | List excluded users |
| PUT | $(@{Tag=IDP configuration; Method=PUT; Path=/v4/iam/idp-configs/{idpConfigId}/users-excluded; OperationId=put-idp-config-users-excluded; Summary=Update excluded users; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=PUT; Path=/v4/iam/idp-configs/{idpConfigId}/users-excluded; OperationId=put-idp-config-users-excluded; Summary=Update excluded users; Deprecated=}.OperationId) | Update excluded users |
| GET | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}/users-included; OperationId=get-idp-config-users-included; Summary=List included users; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=GET; Path=/v4/iam/idp-configs/{idpConfigId}/users-included; OperationId=get-idp-config-users-included; Summary=List included users; Deprecated=}.OperationId) | List included users |
| PUT | $(@{Tag=IDP configuration; Method=PUT; Path=/v4/iam/idp-configs/{idpConfigId}/users-included; OperationId=put-idp-config-users-included; Summary=Update included users; Deprecated=}.Path) | $(@{Tag=IDP configuration; Method=PUT; Path=/v4/iam/idp-configs/{idpConfigId}/users-included; OperationId=put-idp-config-users-included; Summary=Update included users; Deprecated=}.OperationId) | Update included users |

## Image sharing

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/{imageId}/sharegroups; OperationId=get-images-sharegroups-image; Summary=List share groups by image; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/{imageId}/sharegroups; OperationId=get-images-sharegroups-image; Summary=List share groups by image; Deprecated=}.OperationId) | List share groups by image |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups; OperationId=get-sharegroups; Summary=List share groups; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups; OperationId=get-sharegroups; Summary=List share groups; Deprecated=}.OperationId) | List share groups |
| POST | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups; OperationId=post-sharegroups; Summary=Create a share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups; OperationId=post-sharegroups; Summary=Create a share group; Deprecated=}.OperationId) | Create a share group |
| DELETE | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/{sharegroupId}; OperationId=delete-sharegroup; Summary=Delete a share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/{sharegroupId}; OperationId=delete-sharegroup; Summary=Delete a share group; Deprecated=}.OperationId) | Delete a share group |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}; OperationId=get-sharegroup; Summary=Get a share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}; OperationId=get-sharegroup; Summary=Get a share group; Deprecated=}.OperationId) | Get a share group |
| PUT | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/{sharegroupId}; OperationId=put-sharegroup; Summary=Update a share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/{sharegroupId}; OperationId=put-sharegroup; Summary=Update a share group; Deprecated=}.OperationId) | Update a share group |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}/images; OperationId=get-sharegroup-images; Summary=List shared images by group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}/images; OperationId=get-sharegroup-images; Summary=List shared images by group; Deprecated=}.OperationId) | List shared images by group |
| POST | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups/{sharegroupId}/images; OperationId=post-sharegroup-images; Summary=Add images to a share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups/{sharegroupId}/images; OperationId=post-sharegroup-images; Summary=Add images to a share group; Deprecated=}.OperationId) | Add images to a share group |
| DELETE | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/{sharegroupId}/images/{imageId}; OperationId=delete-sharegroup-imageshare; Summary=Revoke access to a shared image; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/{sharegroupId}/images/{imageId}; OperationId=delete-sharegroup-imageshare; Summary=Revoke access to a shared image; Deprecated=}.OperationId) | Revoke access to a shared image |
| PUT | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/{sharegroupId}/images/{imageId}; OperationId=put-sharegroup-imageshare; Summary=Update a shared image; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/{sharegroupId}/images/{imageId}; OperationId=put-sharegroup-imageshare; Summary=Update a shared image; Deprecated=}.OperationId) | Update a shared image |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}/members; OperationId=get-sharegroup-members; Summary=List members by share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}/members; OperationId=get-sharegroup-members; Summary=List members by share group; Deprecated=}.OperationId) | List members by share group |
| POST | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups/{sharegroupId}/members; OperationId=post-sharegroup-members; Summary=Add members to a share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups/{sharegroupId}/members; OperationId=post-sharegroup-members; Summary=Add members to a share group; Deprecated=}.OperationId) | Add members to a share group |
| DELETE | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/{sharegroupId}/members/{tokenUuid}; OperationId=delete-sharegroup-member-token; Summary=Revoke a membership token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/{sharegroupId}/members/{tokenUuid}; OperationId=delete-sharegroup-member-token; Summary=Revoke a membership token; Deprecated=}.OperationId) | Revoke a membership token |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}/members/{tokenUuid}; OperationId=get-sharegroup-member-token; Summary=Get a membership token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/{sharegroupId}/members/{tokenUuid}; OperationId=get-sharegroup-member-token; Summary=Get a membership token; Deprecated=}.OperationId) | Get a membership token |
| PUT | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/{sharegroupId}/members/{tokenUuid}; OperationId=put-sharegroup-member-token; Summary=Update a membership token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/{sharegroupId}/members/{tokenUuid}; OperationId=put-sharegroup-member-token; Summary=Update a membership token; Deprecated=}.OperationId) | Update a membership token |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens; OperationId=get-user-tokens; Summary=List a user's tokens; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens; OperationId=get-user-tokens; Summary=List a user's tokens; Deprecated=}.OperationId) | List a user's tokens |
| POST | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups/tokens; OperationId=post-sharegroup-tokens; Summary=Create a token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=POST; Path=/v4/images/sharegroups/tokens; OperationId=post-sharegroup-tokens; Summary=Create a token; Deprecated=}.OperationId) | Create a token |
| DELETE | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/tokens/{tokenUuid}; OperationId=delete-sharegroup-token; Summary=Delete a token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=DELETE; Path=/v4/images/sharegroups/tokens/{tokenUuid}; OperationId=delete-sharegroup-token; Summary=Delete a token; Deprecated=}.OperationId) | Delete a token |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens/{tokenUuid}; OperationId=get-sharegroup-token; Summary=Get a token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens/{tokenUuid}; OperationId=get-sharegroup-token; Summary=Get a token; Deprecated=}.OperationId) | Get a token |
| PUT | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/tokens/{tokenUuid}; OperationId=put-sharegroup-token; Summary=Update a token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=PUT; Path=/v4/images/sharegroups/tokens/{tokenUuid}; OperationId=put-sharegroup-token; Summary=Update a token; Deprecated=}.OperationId) | Update a token |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens/{tokenUuid}/sharegroup; OperationId=get-sharegroup-by-token; Summary=Get a token's share group; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens/{tokenUuid}/sharegroup; OperationId=get-sharegroup-by-token; Summary=Get a token's share group; Deprecated=}.OperationId) | Get a token's share group |
| GET | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens/{tokenUuid}/sharegroup/images; OperationId=get-sharegroup-images-by-token; Summary=List images by token; Deprecated=}.Path) | $(@{Tag=Image sharing; Method=GET; Path=/v4/images/sharegroups/tokens/{tokenUuid}/sharegroup/images; OperationId=get-sharegroup-images-by-token; Summary=List images by token; Deprecated=}.OperationId) | List images by token |

## Images

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Images; Method=GET; Path=/v4/images; OperationId=get-images; Summary=List images; Deprecated=}.Path) | $(@{Tag=Images; Method=GET; Path=/v4/images; OperationId=get-images; Summary=List images; Deprecated=}.OperationId) | List images |
| POST | $(@{Tag=Images; Method=POST; Path=/v4/images; OperationId=post-image; Summary=Create an image; Deprecated=}.Path) | $(@{Tag=Images; Method=POST; Path=/v4/images; OperationId=post-image; Summary=Create an image; Deprecated=}.OperationId) | Create an image |
| DELETE | $(@{Tag=Images; Method=DELETE; Path=/v4/images/{imageId}; OperationId=delete-image; Summary=Delete an image; Deprecated=}.Path) | $(@{Tag=Images; Method=DELETE; Path=/v4/images/{imageId}; OperationId=delete-image; Summary=Delete an image; Deprecated=}.OperationId) | Delete an image |
| GET | $(@{Tag=Images; Method=GET; Path=/v4/images/{imageId}; OperationId=get-image; Summary=Get an image; Deprecated=}.Path) | $(@{Tag=Images; Method=GET; Path=/v4/images/{imageId}; OperationId=get-image; Summary=Get an image; Deprecated=}.OperationId) | Get an image |
| PUT | $(@{Tag=Images; Method=PUT; Path=/v4/images/{imageId}; OperationId=put-image; Summary=Update an image; Deprecated=}.Path) | $(@{Tag=Images; Method=PUT; Path=/v4/images/{imageId}; OperationId=put-image; Summary=Update an image; Deprecated=}.OperationId) | Update an image |
| POST | $(@{Tag=Images; Method=POST; Path=/v4/images/{imageId}/regions; OperationId=post-replicate-image; Summary=Replicate an image; Deprecated=}.Path) | $(@{Tag=Images; Method=POST; Path=/v4/images/{imageId}/regions; OperationId=post-replicate-image; Summary=Replicate an image; Deprecated=}.OperationId) | Replicate an image |
| POST | $(@{Tag=Images; Method=POST; Path=/v4/images/upload; OperationId=post-upload-image; Summary=Upload an image; Deprecated=}.Path) | $(@{Tag=Images; Method=POST; Path=/v4/images/upload; OperationId=post-upload-image; Summary=Upload an image; Deprecated=}.OperationId) | Upload an image |

## Invoices

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Invoices; Method=GET; Path=/v4/account/invoices; OperationId=get-invoices; Summary=List invoices; Deprecated=}.Path) | $(@{Tag=Invoices; Method=GET; Path=/v4/account/invoices; OperationId=get-invoices; Summary=List invoices; Deprecated=}.OperationId) | List invoices |
| GET | $(@{Tag=Invoices; Method=GET; Path=/v4/account/invoices/{invoiceId}; OperationId=get-invoice; Summary=Get an invoice; Deprecated=}.Path) | $(@{Tag=Invoices; Method=GET; Path=/v4/account/invoices/{invoiceId}; OperationId=get-invoice; Summary=Get an invoice; Deprecated=}.OperationId) | Get an invoice |
| GET | $(@{Tag=Invoices; Method=GET; Path=/v4/account/invoices/{invoiceId}/items; OperationId=get-invoice-items; Summary=List invoice items; Deprecated=}.Path) | $(@{Tag=Invoices; Method=GET; Path=/v4/account/invoices/{invoiceId}/items; OperationId=get-invoice-items; Summary=List invoice items; Deprecated=}.OperationId) | List invoice items |

## IP addresses

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=IP addresses; Method=GET; Path=/v4/networking/ips; OperationId=get-ips; Summary=List IP addresses; Deprecated=}.Path) | $(@{Tag=IP addresses; Method=GET; Path=/v4/networking/ips; OperationId=get-ips; Summary=List IP addresses; Deprecated=}.OperationId) | List IP addresses |
| POST | $(@{Tag=IP addresses; Method=POST; Path=/v4/networking/ips; OperationId=post-allocate-ip; Summary=Allocate an IP address; Deprecated=}.Path) | $(@{Tag=IP addresses; Method=POST; Path=/v4/networking/ips; OperationId=post-allocate-ip; Summary=Allocate an IP address; Deprecated=}.OperationId) | Allocate an IP address |
| GET | $(@{Tag=IP addresses; Method=GET; Path=/v4/networking/ips/{address}; OperationId=get-ip; Summary=Get an IP address; Deprecated=}.Path) | $(@{Tag=IP addresses; Method=GET; Path=/v4/networking/ips/{address}; OperationId=get-ip; Summary=Get an IP address; Deprecated=}.OperationId) | Get an IP address |
| PUT | $(@{Tag=IP addresses; Method=PUT; Path=/v4/networking/ips/{address}; OperationId=put-ip; Summary=Update an IP address's RDNS; Deprecated=}.Path) | $(@{Tag=IP addresses; Method=PUT; Path=/v4/networking/ips/{address}; OperationId=put-ip; Summary=Update an IP address's RDNS; Deprecated=}.OperationId) | Update an IP address's RDNS |
| POST | $(@{Tag=IP addresses; Method=POST; Path=/v4/networking/ips/assign; OperationId=post-assign-ips; Summary=Assign IP addresses; Deprecated=}.Path) | $(@{Tag=IP addresses; Method=POST; Path=/v4/networking/ips/assign; OperationId=post-assign-ips; Summary=Assign IP addresses; Deprecated=}.OperationId) | Assign IP addresses |
| POST | $(@{Tag=IP addresses; Method=POST; Path=/v4/networking/ips/share; OperationId=post-share-ips; Summary=Share IP addresses; Deprecated=}.Path) | $(@{Tag=IP addresses; Method=POST; Path=/v4/networking/ips/share; OperationId=post-share-ips; Summary=Share IP addresses; Deprecated=}.OperationId) | Share IP addresses |

## IPv4 addresses

| Method | Path | operationId | Summary |
|---|---|---|---|
| POST | $(@{Tag=IPv4 addresses; Method=POST; Path=/v4/networking/ipv4/assign; OperationId=post-assign-ipv4s; Summary=Assign IPv4s to Linodes; Deprecated=}.Path) | $(@{Tag=IPv4 addresses; Method=POST; Path=/v4/networking/ipv4/assign; OperationId=post-assign-ipv4s; Summary=Assign IPv4s to Linodes; Deprecated=}.OperationId) | Assign IPv4s to Linodes |
| POST | $(@{Tag=IPv4 addresses; Method=POST; Path=/v4/networking/ipv4/share; OperationId=post-share-ipv4s; Summary=Configure IPv4 sharing; Deprecated=}.Path) | $(@{Tag=IPv4 addresses; Method=POST; Path=/v4/networking/ipv4/share; OperationId=post-share-ipv4s; Summary=Configure IPv4 sharing; Deprecated=}.OperationId) | Configure IPv4 sharing |

## IPv6 pools

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=IPv6 pools; Method=GET; Path=/v4/networking/ipv6/pools; OperationId=get-ipv6-pools; Summary=List IPv6 pools; Deprecated=}.Path) | $(@{Tag=IPv6 pools; Method=GET; Path=/v4/networking/ipv6/pools; OperationId=get-ipv6-pools; Summary=List IPv6 pools; Deprecated=}.OperationId) | List IPv6 pools |

## IPv6 ranges

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=IPv6 ranges; Method=GET; Path=/v4/networking/ipv6/ranges; OperationId=get-ipv6-ranges; Summary=List IPv6 ranges; Deprecated=}.Path) | $(@{Tag=IPv6 ranges; Method=GET; Path=/v4/networking/ipv6/ranges; OperationId=get-ipv6-ranges; Summary=List IPv6 ranges; Deprecated=}.OperationId) | List IPv6 ranges |
| POST | $(@{Tag=IPv6 ranges; Method=POST; Path=/v4/networking/ipv6/ranges; OperationId=post-ipv6-range; Summary=Create an IPv6 range; Deprecated=}.Path) | $(@{Tag=IPv6 ranges; Method=POST; Path=/v4/networking/ipv6/ranges; OperationId=post-ipv6-range; Summary=Create an IPv6 range; Deprecated=}.OperationId) | Create an IPv6 range |
| DELETE | $(@{Tag=IPv6 ranges; Method=DELETE; Path=/v4/networking/ipv6/ranges/{range}; OperationId=delete-ipv6-range; Summary=Delete an IPv6 range; Deprecated=}.Path) | $(@{Tag=IPv6 ranges; Method=DELETE; Path=/v4/networking/ipv6/ranges/{range}; OperationId=delete-ipv6-range; Summary=Delete an IPv6 range; Deprecated=}.OperationId) | Delete an IPv6 range |
| GET | $(@{Tag=IPv6 ranges; Method=GET; Path=/v4/networking/ipv6/ranges/{range}; OperationId=get-ipv6-range; Summary=Get an IPv6 range; Deprecated=}.Path) | $(@{Tag=IPv6 ranges; Method=GET; Path=/v4/networking/ipv6/ranges/{range}; OperationId=get-ipv6-range; Summary=Get an IPv6 range; Deprecated=}.OperationId) | Get an IPv6 range |

## Kubeconfigs

| Method | Path | operationId | Summary |
|---|---|---|---|
| DELETE | $(@{Tag=Kubeconfigs; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/kubeconfig; OperationId=delete-lke-cluster-kubeconfig; Summary=Delete a Kubeconfig; Deprecated=}.Path) | $(@{Tag=Kubeconfigs; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/kubeconfig; OperationId=delete-lke-cluster-kubeconfig; Summary=Delete a Kubeconfig; Deprecated=}.OperationId) | Delete a Kubeconfig |
| GET | $(@{Tag=Kubeconfigs; Method=GET; Path=/v4/lke/clusters/{clusterId}/kubeconfig; OperationId=get-lke-cluster-kubeconfig; Summary=Get a Kubeconfig; Deprecated=}.Path) | $(@{Tag=Kubeconfigs; Method=GET; Path=/v4/lke/clusters/{clusterId}/kubeconfig; OperationId=get-lke-cluster-kubeconfig; Summary=Get a Kubeconfig; Deprecated=}.OperationId) | Get a Kubeconfig |

## Linode disk backups

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode disk backups; Method=GET; Path=/v4/linode/instances/{linodeId}/backups; OperationId=get-backups; Summary=List backups; Deprecated=}.Path) | $(@{Tag=Linode disk backups; Method=GET; Path=/v4/linode/instances/{linodeId}/backups; OperationId=get-backups; Summary=List backups; Deprecated=}.OperationId) | List backups |
| POST | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups; OperationId=post-snapshot; Summary=Create a snapshot; Deprecated=}.Path) | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups; OperationId=post-snapshot; Summary=Create a snapshot; Deprecated=}.OperationId) | Create a snapshot |
| GET | $(@{Tag=Linode disk backups; Method=GET; Path=/v4/linode/instances/{linodeId}/backups/{backupId}; OperationId=get-backup; Summary=Get a backup; Deprecated=}.Path) | $(@{Tag=Linode disk backups; Method=GET; Path=/v4/linode/instances/{linodeId}/backups/{backupId}; OperationId=get-backup; Summary=Get a backup; Deprecated=}.OperationId) | Get a backup |
| POST | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups/{backupId}/restore; OperationId=post-restore-backup; Summary=Restore a backup; Deprecated=}.Path) | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups/{backupId}/restore; OperationId=post-restore-backup; Summary=Restore a backup; Deprecated=}.OperationId) | Restore a backup |
| POST | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups/cancel; OperationId=post-cancel-backups; Summary=Cancel backups; Deprecated=}.Path) | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups/cancel; OperationId=post-cancel-backups; Summary=Cancel backups; Deprecated=}.OperationId) | Cancel backups |
| POST | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups/enable; OperationId=post-enable-backups; Summary=Enable backups; Deprecated=}.Path) | $(@{Tag=Linode disk backups; Method=POST; Path=/v4/linode/instances/{linodeId}/backups/enable; OperationId=post-enable-backups; Summary=Enable backups; Deprecated=}.OperationId) | Enable backups |

## Linode disks

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode disks; Method=GET; Path=/v4/linode/instances/{linodeId}/disks; OperationId=get-linode-disks; Summary=List disks; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=GET; Path=/v4/linode/instances/{linodeId}/disks; OperationId=get-linode-disks; Summary=List disks; Deprecated=}.OperationId) | List disks |
| POST | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks; OperationId=post-add-linode-disk; Summary=Create a disk; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks; OperationId=post-add-linode-disk; Summary=Create a disk; Deprecated=}.OperationId) | Create a disk |
| DELETE | $(@{Tag=Linode disks; Method=DELETE; Path=/v4/linode/instances/{linodeId}/disks/{diskId}; OperationId=delete-disk; Summary=Delete a disk; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=DELETE; Path=/v4/linode/instances/{linodeId}/disks/{diskId}; OperationId=delete-disk; Summary=Delete a disk; Deprecated=}.OperationId) | Delete a disk |
| GET | $(@{Tag=Linode disks; Method=GET; Path=/v4/linode/instances/{linodeId}/disks/{diskId}; OperationId=get-linode-disk; Summary=Get a disk; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=GET; Path=/v4/linode/instances/{linodeId}/disks/{diskId}; OperationId=get-linode-disk; Summary=Get a disk; Deprecated=}.OperationId) | Get a disk |
| PUT | $(@{Tag=Linode disks; Method=PUT; Path=/v4/linode/instances/{linodeId}/disks/{diskId}; OperationId=put-disk; Summary=Update a disk; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=PUT; Path=/v4/linode/instances/{linodeId}/disks/{diskId}; OperationId=put-disk; Summary=Update a disk; Deprecated=}.OperationId) | Update a disk |
| POST | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks/{diskId}/clone; OperationId=post-clone-linode-disk; Summary=Clone a disk; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks/{diskId}/clone; OperationId=post-clone-linode-disk; Summary=Clone a disk; Deprecated=}.OperationId) | Clone a disk |
| POST | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks/{diskId}/password; OperationId=post-reset-disk-password; Summary=Reset a disk root password; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks/{diskId}/password; OperationId=post-reset-disk-password; Summary=Reset a disk root password; Deprecated=}.OperationId) | Reset a disk root password |
| POST | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks/{diskId}/resize; OperationId=post-resize-disk; Summary=Resize a disk; Deprecated=}.Path) | $(@{Tag=Linode disks; Method=POST; Path=/v4/linode/instances/{linodeId}/disks/{diskId}/resize; OperationId=post-resize-disk; Summary=Resize a disk; Deprecated=}.OperationId) | Resize a disk |

## Linode firewalls

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode firewalls; Method=GET; Path=/v4/linode/instances/{linodeId}/firewalls; OperationId=get-linode-firewalls; Summary=List a Linode's firewalls; Deprecated=}.Path) | $(@{Tag=Linode firewalls; Method=GET; Path=/v4/linode/instances/{linodeId}/firewalls; OperationId=get-linode-firewalls; Summary=List a Linode's firewalls; Deprecated=}.OperationId) | List a Linode's firewalls |
| PUT | $(@{Tag=Linode firewalls; Method=PUT; Path=/v4/linode/instances/{linodeId}/firewalls; OperationId=put-linode-firewalls; Summary=Update a Linode's firewalls; Deprecated=}.Path) | $(@{Tag=Linode firewalls; Method=PUT; Path=/v4/linode/instances/{linodeId}/firewalls; OperationId=put-linode-firewalls; Summary=Update a Linode's firewalls; Deprecated=}.OperationId) | Update a Linode's firewalls |
| POST | $(@{Tag=Linode firewalls; Method=POST; Path=/v4/linode/instances/{linodeId}/firewalls/apply; OperationId=post-apply-firewalls; Summary=Apply a Linode's firewalls; Deprecated=}.Path) | $(@{Tag=Linode firewalls; Method=POST; Path=/v4/linode/instances/{linodeId}/firewalls/apply; OperationId=post-apply-firewalls; Summary=Apply a Linode's firewalls; Deprecated=}.OperationId) | Apply a Linode's firewalls |

## Linode instances

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode instances; Method=GET; Path=/v4/linode/instances; OperationId=get-linode-instances; Summary=List Linodes; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=GET; Path=/v4/linode/instances; OperationId=get-linode-instances; Summary=List Linodes; Deprecated=}.OperationId) | List Linodes |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances; OperationId=post-linode-instance; Summary=Create a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances; OperationId=post-linode-instance; Summary=Create a Linode; Deprecated=}.OperationId) | Create a Linode |
| DELETE | $(@{Tag=Linode instances; Method=DELETE; Path=/v4/linode/instances/{linodeId}; OperationId=delete-linode-instance; Summary=Delete a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=DELETE; Path=/v4/linode/instances/{linodeId}; OperationId=delete-linode-instance; Summary=Delete a Linode; Deprecated=}.OperationId) | Delete a Linode |
| GET | $(@{Tag=Linode instances; Method=GET; Path=/v4/linode/instances/{linodeId}; OperationId=get-linode-instance; Summary=Get a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=GET; Path=/v4/linode/instances/{linodeId}; OperationId=get-linode-instance; Summary=Get a Linode; Deprecated=}.OperationId) | Get a Linode |
| PUT | $(@{Tag=Linode instances; Method=PUT; Path=/v4/linode/instances/{linodeId}; OperationId=put-linode-instance; Summary=Update a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=PUT; Path=/v4/linode/instances/{linodeId}; OperationId=put-linode-instance; Summary=Update a Linode; Deprecated=}.OperationId) | Update a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/boot; OperationId=post-boot-linode-instance; Summary=Boot a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/boot; OperationId=post-boot-linode-instance; Summary=Boot a Linode; Deprecated=}.OperationId) | Boot a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/clone; OperationId=post-clone-linode-instance; Summary=Clone a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/clone; OperationId=post-clone-linode-instance; Summary=Clone a Linode; Deprecated=}.OperationId) | Clone a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/migrate; OperationId=post-migrate-linode-instance; Summary=Migrate a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/migrate; OperationId=post-migrate-linode-instance; Summary=Migrate a Linode; Deprecated=}.OperationId) | Migrate a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/mutate; OperationId=post-mutate-linode-instance; Summary=Upgrade a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/mutate; OperationId=post-mutate-linode-instance; Summary=Upgrade a Linode; Deprecated=}.OperationId) | Upgrade a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/password; OperationId=post-reset-linode-password; Summary=Reset a Linode's root password; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/password; OperationId=post-reset-linode-password; Summary=Reset a Linode's root password; Deprecated=}.OperationId) | Reset a Linode's root password |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/reboot; OperationId=post-reboot-linode-instance; Summary=Reboot a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/reboot; OperationId=post-reboot-linode-instance; Summary=Reboot a Linode; Deprecated=}.OperationId) | Reboot a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/rebuild; OperationId=post-rebuild-linode-instance; Summary=Rebuild a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/rebuild; OperationId=post-rebuild-linode-instance; Summary=Rebuild a Linode; Deprecated=}.OperationId) | Rebuild a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/rescue; OperationId=post-rescue-linode-instance; Summary=Boot a Linode into rescue mode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/rescue; OperationId=post-rescue-linode-instance; Summary=Boot a Linode into rescue mode; Deprecated=}.OperationId) | Boot a Linode into rescue mode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/resize; OperationId=post-resize-linode-instance; Summary=Resize a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/resize; OperationId=post-resize-linode-instance; Summary=Resize a Linode; Deprecated=}.OperationId) | Resize a Linode |
| POST | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/shutdown; OperationId=post-shutdown-linode-instance; Summary=Shut down a Linode; Deprecated=}.Path) | $(@{Tag=Linode instances; Method=POST; Path=/v4/linode/instances/{linodeId}/shutdown; OperationId=post-shutdown-linode-instance; Summary=Shut down a Linode; Deprecated=}.OperationId) | Shut down a Linode |

## Linode interfaces

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces; OperationId=get-linode-interfaces; Summary=List Linode interfaces; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces; OperationId=get-linode-interfaces; Summary=List Linode interfaces; Deprecated=}.OperationId) | List Linode interfaces |
| POST | $(@{Tag=Linode interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/interfaces; OperationId=post-linode-interface; Summary=Add a Linode interface; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/interfaces; OperationId=post-linode-interface; Summary=Add a Linode interface; Deprecated=}.OperationId) | Add a Linode interface |
| DELETE | $(@{Tag=Linode interfaces; Method=DELETE; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}; OperationId=delete-linode-interface; Summary=Delete a Linode interface; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=DELETE; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}; OperationId=delete-linode-interface; Summary=Delete a Linode interface; Deprecated=}.OperationId) | Delete a Linode interface |
| GET | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}; OperationId=get-linode-interface; Summary=Get a Linode interface; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}; OperationId=get-linode-interface; Summary=Get a Linode interface; Deprecated=}.OperationId) | Get a Linode interface |
| PUT | $(@{Tag=Linode interfaces; Method=PUT; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}; OperationId=put-linode-interface; Summary=Update a Linode interface; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=PUT; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}; OperationId=put-linode-interface; Summary=Update a Linode interface; Deprecated=}.OperationId) | Update a Linode interface |
| GET | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}/firewalls; OperationId=get-linode-interface-firewalls; Summary=List Linode interface firewalls; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/{interfaceId}/firewalls; OperationId=get-linode-interface-firewalls; Summary=List Linode interface firewalls; Deprecated=}.OperationId) | List Linode interface firewalls |
| GET | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/history; OperationId=get-linode-interface-history; Summary=List a Linode's network interface history; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/history; OperationId=get-linode-interface-history; Summary=List a Linode's network interface history; Deprecated=}.OperationId) | List a Linode's network interface history |
| GET | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/settings; OperationId=get-linode-interface-settings; Summary=List Linode interface settings; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=GET; Path=/v4/linode/instances/{linodeId}/interfaces/settings; OperationId=get-linode-interface-settings; Summary=List Linode interface settings; Deprecated=}.OperationId) | List Linode interface settings |
| PUT | $(@{Tag=Linode interfaces; Method=PUT; Path=/v4/linode/instances/{linodeId}/interfaces/settings; OperationId=put-linode-interface-settings; Summary=Update Linode interface settings; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=PUT; Path=/v4/linode/instances/{linodeId}/interfaces/settings; OperationId=put-linode-interface-settings; Summary=Update Linode interface settings; Deprecated=}.OperationId) | Update Linode interface settings |
| POST | $(@{Tag=Linode interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/upgrade-interfaces; OperationId=post-upgrade-linode-interfaces; Summary=Upgrade to Linode interfaces; Deprecated=}.Path) | $(@{Tag=Linode interfaces; Method=POST; Path=/v4/linode/instances/{linodeId}/upgrade-interfaces; OperationId=post-upgrade-linode-interfaces; Summary=Upgrade to Linode interfaces; Deprecated=}.OperationId) | Upgrade to Linode interfaces |

## Linode IP addresses

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode IP addresses; Method=GET; Path=/v4/linode/instances/{linodeId}/ips; OperationId=get-linode-ips; Summary=Get networking information; Deprecated=}.Path) | $(@{Tag=Linode IP addresses; Method=GET; Path=/v4/linode/instances/{linodeId}/ips; OperationId=get-linode-ips; Summary=Get networking information; Deprecated=}.OperationId) | Get networking information |
| POST | $(@{Tag=Linode IP addresses; Method=POST; Path=/v4/linode/instances/{linodeId}/ips; OperationId=post-add-linode-ip; Summary=Allocate an IPv4 address; Deprecated=}.Path) | $(@{Tag=Linode IP addresses; Method=POST; Path=/v4/linode/instances/{linodeId}/ips; OperationId=post-add-linode-ip; Summary=Allocate an IPv4 address; Deprecated=}.OperationId) | Allocate an IPv4 address |
| DELETE | $(@{Tag=Linode IP addresses; Method=DELETE; Path=/v4/linode/instances/{linodeId}/ips/{address}; OperationId=delete-linode-ip; Summary=Delete an IPv4 address; Deprecated=}.Path) | $(@{Tag=Linode IP addresses; Method=DELETE; Path=/v4/linode/instances/{linodeId}/ips/{address}; OperationId=delete-linode-ip; Summary=Delete an IPv4 address; Deprecated=}.OperationId) | Delete an IPv4 address |
| GET | $(@{Tag=Linode IP addresses; Method=GET; Path=/v4/linode/instances/{linodeId}/ips/{address}; OperationId=get-linode-ip; Summary=Get a Linode's IP address; Deprecated=}.Path) | $(@{Tag=Linode IP addresses; Method=GET; Path=/v4/linode/instances/{linodeId}/ips/{address}; OperationId=get-linode-ip; Summary=Get a Linode's IP address; Deprecated=}.OperationId) | Get a Linode's IP address |
| PUT | $(@{Tag=Linode IP addresses; Method=PUT; Path=/v4/linode/instances/{linodeId}/ips/{address}; OperationId=put-linode-ip; Summary=Update an IP address's RDNS for a Linode; Deprecated=}.Path) | $(@{Tag=Linode IP addresses; Method=PUT; Path=/v4/linode/instances/{linodeId}/ips/{address}; OperationId=put-linode-ip; Summary=Update an IP address's RDNS for a Linode; Deprecated=}.OperationId) | Update an IP address's RDNS for a Linode |

## Linode kernels

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode kernels; Method=GET; Path=/v4/linode/kernels; OperationId=get-kernels; Summary=List kernels; Deprecated=}.Path) | $(@{Tag=Linode kernels; Method=GET; Path=/v4/linode/kernels; OperationId=get-kernels; Summary=List kernels; Deprecated=}.OperationId) | List kernels |
| GET | $(@{Tag=Linode kernels; Method=GET; Path=/v4/linode/kernels/{kernelId}; OperationId=get-kernel; Summary=Get a kernel; Deprecated=}.Path) | $(@{Tag=Linode kernels; Method=GET; Path=/v4/linode/kernels/{kernelId}; OperationId=get-kernel; Summary=Get a kernel; Deprecated=}.OperationId) | Get a kernel |

## Linode NodeBalancers

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode NodeBalancers; Method=GET; Path=/v4/linode/instances/{linodeId}/nodebalancers; OperationId=get-linode-node-balancers; Summary=List Linode NodeBalancers; Deprecated=}.Path) | $(@{Tag=Linode NodeBalancers; Method=GET; Path=/v4/linode/instances/{linodeId}/nodebalancers; OperationId=get-linode-node-balancers; Summary=List Linode NodeBalancers; Deprecated=}.OperationId) | List Linode NodeBalancers |

## Linode statistics

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/stats; OperationId=get-linode-stats; Summary=Get daily Linode statistics; Deprecated=}.Path) | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/stats; OperationId=get-linode-stats; Summary=Get daily Linode statistics; Deprecated=}.OperationId) | Get daily Linode statistics |
| GET | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/stats/{year}/{month}; OperationId=get-linode-stats-by-year-month; Summary=Get a month's Linode statistics; Deprecated=}.Path) | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/stats/{year}/{month}; OperationId=get-linode-stats-by-year-month; Summary=Get a month's Linode statistics; Deprecated=}.OperationId) | Get a month's Linode statistics |
| GET | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/transfer; OperationId=get-linode-transfer; Summary=Get this month's network transfer stats; Deprecated=}.Path) | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/transfer; OperationId=get-linode-transfer; Summary=Get this month's network transfer stats; Deprecated=}.OperationId) | Get this month's network transfer stats |
| GET | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/transfer/{year}/{month}; OperationId=get-linode-transfer-by-year-month; Summary=Get monthly network transfer stats; Deprecated=}.Path) | $(@{Tag=Linode statistics; Method=GET; Path=/v4/linode/instances/{linodeId}/transfer/{year}/{month}; OperationId=get-linode-transfer-by-year-month; Summary=Get monthly network transfer stats; Deprecated=}.OperationId) | Get monthly network transfer stats |
| GET | $(@{Tag=Linode statistics; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/stats; OperationId=get-node-balancer-stats; Summary=Get NodeBalancer statistics; Deprecated=}.Path) | $(@{Tag=Linode statistics; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/stats; OperationId=get-node-balancer-stats; Summary=Get NodeBalancer statistics; Deprecated=}.OperationId) | Get NodeBalancer statistics |

## Linode types

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode types; Method=GET; Path=/v4/linode/types; OperationId=get-linode-types; Summary=List types; Deprecated=}.Path) | $(@{Tag=Linode types; Method=GET; Path=/v4/linode/types; OperationId=get-linode-types; Summary=List types; Deprecated=}.OperationId) | List types |
| GET | $(@{Tag=Linode types; Method=GET; Path=/v4/linode/types/{typeId}; OperationId=get-linode-type; Summary=Get a type; Deprecated=}.Path) | $(@{Tag=Linode types; Method=GET; Path=/v4/linode/types/{typeId}; OperationId=get-linode-type; Summary=Get a type; Deprecated=}.OperationId) | Get a type |

## Linode volumes

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Linode volumes; Method=GET; Path=/v4/linode/instances/{linodeId}/volumes; OperationId=get-linode-volumes; Summary=List a Linode's volumes; Deprecated=}.Path) | $(@{Tag=Linode volumes; Method=GET; Path=/v4/linode/instances/{linodeId}/volumes; OperationId=get-linode-volumes; Summary=List a Linode's volumes; Deprecated=}.OperationId) | List a Linode's volumes |

## LKE API endpoints

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=LKE API endpoints; Method=GET; Path=/v4/lke/clusters/{clusterId}/api-endpoints; OperationId=get-lke-cluster-api-endpoints; Summary=List Kubernetes API endpoints; Deprecated=}.Path) | $(@{Tag=LKE API endpoints; Method=GET; Path=/v4/lke/clusters/{clusterId}/api-endpoints; OperationId=get-lke-cluster-api-endpoints; Summary=List Kubernetes API endpoints; Deprecated=}.OperationId) | List Kubernetes API endpoints |

## LKE cluster dashboard

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=LKE cluster dashboard; Method=GET; Path=/v4/lke/clusters/{clusterId}/dashboard; OperationId=get-lke-cluster-dashboard; Summary=Get a Kubernetes cluster dashboard URL; Deprecated=}.Path) | $(@{Tag=LKE cluster dashboard; Method=GET; Path=/v4/lke/clusters/{clusterId}/dashboard; OperationId=get-lke-cluster-dashboard; Summary=Get a Kubernetes cluster dashboard URL; Deprecated=}.OperationId) | Get a Kubernetes cluster dashboard URL |

## LKE clusters

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=LKE clusters; Method=GET; Path=/v4/lke/clusters; OperationId=get-lke-clusters; Summary=List Kubernetes clusters; Deprecated=}.Path) | $(@{Tag=LKE clusters; Method=GET; Path=/v4/lke/clusters; OperationId=get-lke-clusters; Summary=List Kubernetes clusters; Deprecated=}.OperationId) | List Kubernetes clusters |
| POST | $(@{Tag=LKE clusters; Method=POST; Path=/v4/lke/clusters; OperationId=post-lke-cluster; Summary=Create a Kubernetes cluster; Deprecated=}.Path) | $(@{Tag=LKE clusters; Method=POST; Path=/v4/lke/clusters; OperationId=post-lke-cluster; Summary=Create a Kubernetes cluster; Deprecated=}.OperationId) | Create a Kubernetes cluster |
| DELETE | $(@{Tag=LKE clusters; Method=DELETE; Path=/v4/lke/clusters/{clusterId}; OperationId=delete-lke-cluster; Summary=Delete a Kubernetes cluster; Deprecated=}.Path) | $(@{Tag=LKE clusters; Method=DELETE; Path=/v4/lke/clusters/{clusterId}; OperationId=delete-lke-cluster; Summary=Delete a Kubernetes cluster; Deprecated=}.OperationId) | Delete a Kubernetes cluster |
| GET | $(@{Tag=LKE clusters; Method=GET; Path=/v4/lke/clusters/{clusterId}; OperationId=get-lke-cluster; Summary=Get a Kubernetes cluster; Deprecated=}.Path) | $(@{Tag=LKE clusters; Method=GET; Path=/v4/lke/clusters/{clusterId}; OperationId=get-lke-cluster; Summary=Get a Kubernetes cluster; Deprecated=}.OperationId) | Get a Kubernetes cluster |
| PUT | $(@{Tag=LKE clusters; Method=PUT; Path=/v4/lke/clusters/{clusterId}; OperationId=put-lke-cluster; Summary=Update a Kubernetes cluster; Deprecated=}.Path) | $(@{Tag=LKE clusters; Method=PUT; Path=/v4/lke/clusters/{clusterId}; OperationId=put-lke-cluster; Summary=Update a Kubernetes cluster; Deprecated=}.OperationId) | Update a Kubernetes cluster |
| POST | $(@{Tag=LKE clusters; Method=POST; Path=/v4/lke/clusters/{clusterId}/recycle; OperationId=post-lke-cluster-recycle; Summary=Recycle cluster nodes; Deprecated=}.Path) | $(@{Tag=LKE clusters; Method=POST; Path=/v4/lke/clusters/{clusterId}/recycle; OperationId=post-lke-cluster-recycle; Summary=Recycle cluster nodes; Deprecated=}.OperationId) | Recycle cluster nodes |
| POST | $(@{Tag=LKE clusters; Method=POST; Path=/v4/lke/clusters/{clusterId}/regenerate; OperationId=post-lke-cluster-regenerate; Summary=Regenerate a Kubernetes cluster; Deprecated=}.Path) | $(@{Tag=LKE clusters; Method=POST; Path=/v4/lke/clusters/{clusterId}/regenerate; OperationId=post-lke-cluster-regenerate; Summary=Regenerate a Kubernetes cluster; Deprecated=}.OperationId) | Regenerate a Kubernetes cluster |
| GET | $(@{Tag=LKE clusters; Method=GET; Path=/v4/object-storage/clusters/{clusterId}; OperationId=get-object-storage-cluster; Summary=Get a cluster; Deprecated=yes}.Path) | $(@{Tag=LKE clusters; Method=GET; Path=/v4/object-storage/clusters/{clusterId}; OperationId=get-object-storage-cluster; Summary=Get a cluster; Deprecated=yes}.OperationId) | [DEPRECATED] Get a cluster |

## LKE node pools

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=LKE node pools; Method=GET; Path=/v4/lke/clusters/{clusterId}/pools; OperationId=get-lke-cluster-pools; Summary=List node pools; Deprecated=}.Path) | $(@{Tag=LKE node pools; Method=GET; Path=/v4/lke/clusters/{clusterId}/pools; OperationId=get-lke-cluster-pools; Summary=List node pools; Deprecated=}.OperationId) | List node pools |
| POST | $(@{Tag=LKE node pools; Method=POST; Path=/v4/lke/clusters/{clusterId}/pools; OperationId=post-lke-cluster-pools; Summary=Create a node pool; Deprecated=}.Path) | $(@{Tag=LKE node pools; Method=POST; Path=/v4/lke/clusters/{clusterId}/pools; OperationId=post-lke-cluster-pools; Summary=Create a node pool; Deprecated=}.OperationId) | Create a node pool |
| DELETE | $(@{Tag=LKE node pools; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}; OperationId=delete-lke-node-pool; Summary=Delete a node pool; Deprecated=}.Path) | $(@{Tag=LKE node pools; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}; OperationId=delete-lke-node-pool; Summary=Delete a node pool; Deprecated=}.OperationId) | Delete a node pool |
| GET | $(@{Tag=LKE node pools; Method=GET; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}; OperationId=get-lke-node-pool; Summary=Get a node pool; Deprecated=}.Path) | $(@{Tag=LKE node pools; Method=GET; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}; OperationId=get-lke-node-pool; Summary=Get a node pool; Deprecated=}.OperationId) | Get a node pool |
| PUT | $(@{Tag=LKE node pools; Method=PUT; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}; OperationId=put-lke-node-pool; Summary=Update a node pool; Deprecated=}.Path) | $(@{Tag=LKE node pools; Method=PUT; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}; OperationId=put-lke-node-pool; Summary=Update a node pool; Deprecated=}.OperationId) | Update a node pool |
| POST | $(@{Tag=LKE node pools; Method=POST; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}/recycle; OperationId=post-lke-cluster-pool-recycle; Summary=Recycle a node pool; Deprecated=}.Path) | $(@{Tag=LKE node pools; Method=POST; Path=/v4/lke/clusters/{clusterId}/pools/{poolId}/recycle; OperationId=post-lke-cluster-pool-recycle; Summary=Recycle a node pool; Deprecated=}.OperationId) | Recycle a node pool |

## LKE nodes

| Method | Path | operationId | Summary |
|---|---|---|---|
| DELETE | $(@{Tag=LKE nodes; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/nodes/{nodeId}; OperationId=delete-lke-cluster-node; Summary=Delete a node; Deprecated=}.Path) | $(@{Tag=LKE nodes; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/nodes/{nodeId}; OperationId=delete-lke-cluster-node; Summary=Delete a node; Deprecated=}.OperationId) | Delete a node |
| GET | $(@{Tag=LKE nodes; Method=GET; Path=/v4/lke/clusters/{clusterId}/nodes/{nodeId}; OperationId=get-lke-cluster-node; Summary=Get a node; Deprecated=}.Path) | $(@{Tag=LKE nodes; Method=GET; Path=/v4/lke/clusters/{clusterId}/nodes/{nodeId}; OperationId=get-lke-cluster-node; Summary=Get a node; Deprecated=}.OperationId) | Get a node |
| POST | $(@{Tag=LKE nodes; Method=POST; Path=/v4/lke/clusters/{clusterId}/nodes/{nodeId}/recycle; OperationId=post-lke-cluster-node-recycle; Summary=Recycle a node; Deprecated=}.Path) | $(@{Tag=LKE nodes; Method=POST; Path=/v4/lke/clusters/{clusterId}/nodes/{nodeId}/recycle; OperationId=post-lke-cluster-node-recycle; Summary=Recycle a node; Deprecated=}.OperationId) | Recycle a node |

## LKE service tokens

| Method | Path | operationId | Summary |
|---|---|---|---|
| DELETE | $(@{Tag=LKE service tokens; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/servicetoken; OperationId=delete-lke-service-token; Summary=Delete a service token; Deprecated=}.Path) | $(@{Tag=LKE service tokens; Method=DELETE; Path=/v4/lke/clusters/{clusterId}/servicetoken; OperationId=delete-lke-service-token; Summary=Delete a service token; Deprecated=}.OperationId) | Delete a service token |

## LKE types

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=LKE types; Method=GET; Path=/v4/lke/types; OperationId=get-lke-types; Summary=List Kubernetes types; Deprecated=}.Path) | $(@{Tag=LKE types; Method=GET; Path=/v4/lke/types; OperationId=get-lke-types; Summary=List Kubernetes types; Deprecated=}.OperationId) | List Kubernetes types |

## LKE versions

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/tiers/{tier}/versions; OperationId=get-lke-tiers-versions; Summary=List LKE Kubernetes versions (any tier); Deprecated=}.Path) | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/tiers/{tier}/versions; OperationId=get-lke-tiers-versions; Summary=List LKE Kubernetes versions (any tier); Deprecated=}.OperationId) | List LKE Kubernetes versions (any tier) |
| GET | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/tiers/{tier}/versions/{version}; OperationId=get-lke-tiers-version; Summary=Get an LKE Kubernetes version (any tier); Deprecated=}.Path) | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/tiers/{tier}/versions/{version}; OperationId=get-lke-tiers-version; Summary=Get an LKE Kubernetes version (any tier); Deprecated=}.OperationId) | Get an LKE Kubernetes version (any tier) |
| GET | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/versions; OperationId=get-lke-versions; Summary=List LKE Kubernetes versions (non-enterprise); Deprecated=}.Path) | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/versions; OperationId=get-lke-versions; Summary=List LKE Kubernetes versions (non-enterprise); Deprecated=}.OperationId) | List LKE Kubernetes versions (non-enterprise) |
| GET | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/versions/{version}; OperationId=get-lke-version; Summary=Get an LKE Kubernetes version (non-enterprise); Deprecated=}.Path) | $(@{Tag=LKE versions; Method=GET; Path=/v4/lke/versions/{version}; OperationId=get-lke-version; Summary=Get an LKE Kubernetes version (non-enterprise); Deprecated=}.OperationId) | Get an LKE Kubernetes version (non-enterprise) |

## Logs

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams; OperationId=get-streams; Summary=List streams; Deprecated=}.Path) | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams; OperationId=get-streams; Summary=List streams; Deprecated=}.OperationId) | List streams |
| POST | $(@{Tag=Logs; Method=POST; Path=/v4/monitor/streams; OperationId=post-stream; Summary=Create a stream; Deprecated=}.Path) | $(@{Tag=Logs; Method=POST; Path=/v4/monitor/streams; OperationId=post-stream; Summary=Create a stream; Deprecated=}.OperationId) | Create a stream |
| DELETE | $(@{Tag=Logs; Method=DELETE; Path=/v4/monitor/streams/{streamId}; OperationId=delete-stream; Summary=Delete a stream; Deprecated=}.Path) | $(@{Tag=Logs; Method=DELETE; Path=/v4/monitor/streams/{streamId}; OperationId=delete-stream; Summary=Delete a stream; Deprecated=}.OperationId) | Delete a stream |
| GET | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/{streamId}; OperationId=get-stream; Summary=Get a stream; Deprecated=}.Path) | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/{streamId}; OperationId=get-stream; Summary=Get a stream; Deprecated=}.OperationId) | Get a stream |
| PUT | $(@{Tag=Logs; Method=PUT; Path=/v4/monitor/streams/{streamId}; OperationId=put-stream; Summary=Update a stream; Deprecated=}.Path) | $(@{Tag=Logs; Method=PUT; Path=/v4/monitor/streams/{streamId}; OperationId=put-stream; Summary=Update a stream; Deprecated=}.OperationId) | Update a stream |
| GET | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/{streamId}/history; OperationId=get-stream-history; Summary=Get a stream's history; Deprecated=}.Path) | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/{streamId}/history; OperationId=get-stream-history; Summary=Get a stream's history; Deprecated=}.OperationId) | Get a stream's history |
| GET | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/destinations; OperationId=get-destinations; Summary=List destinations; Deprecated=}.Path) | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/destinations; OperationId=get-destinations; Summary=List destinations; Deprecated=}.OperationId) | List destinations |
| POST | $(@{Tag=Logs; Method=POST; Path=/v4/monitor/streams/destinations; OperationId=post-destination; Summary=Create a destination; Deprecated=}.Path) | $(@{Tag=Logs; Method=POST; Path=/v4/monitor/streams/destinations; OperationId=post-destination; Summary=Create a destination; Deprecated=}.OperationId) | Create a destination |
| DELETE | $(@{Tag=Logs; Method=DELETE; Path=/v4/monitor/streams/destinations/{destinationId}; OperationId=delete-destination; Summary=Delete a destination; Deprecated=}.Path) | $(@{Tag=Logs; Method=DELETE; Path=/v4/monitor/streams/destinations/{destinationId}; OperationId=delete-destination; Summary=Delete a destination; Deprecated=}.OperationId) | Delete a destination |
| GET | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/destinations/{destinationId}; OperationId=get-destination; Summary=Get a destination; Deprecated=}.Path) | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/destinations/{destinationId}; OperationId=get-destination; Summary=Get a destination; Deprecated=}.OperationId) | Get a destination |
| PUT | $(@{Tag=Logs; Method=PUT; Path=/v4/monitor/streams/destinations/{destinationId}; OperationId=put-destination; Summary=Update a destination; Deprecated=}.Path) | $(@{Tag=Logs; Method=PUT; Path=/v4/monitor/streams/destinations/{destinationId}; OperationId=put-destination; Summary=Update a destination; Deprecated=}.OperationId) | Update a destination |
| GET | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/destinations/{destinationId}/history; OperationId=get-destination-history; Summary=Get a destination's history; Deprecated=}.Path) | $(@{Tag=Logs; Method=GET; Path=/v4/monitor/streams/destinations/{destinationId}/history; OperationId=get-destination-history; Summary=Get a destination's history; Deprecated=}.OperationId) | Get a destination's history |

## Longview clients

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Longview clients; Method=GET; Path=/v4/longview/clients; OperationId=get-longview-clients; Summary=List Longview clients; Deprecated=}.Path) | $(@{Tag=Longview clients; Method=GET; Path=/v4/longview/clients; OperationId=get-longview-clients; Summary=List Longview clients; Deprecated=}.OperationId) | List Longview clients |
| POST | $(@{Tag=Longview clients; Method=POST; Path=/v4/longview/clients; OperationId=post-longview-client; Summary=Create a Longview client; Deprecated=}.Path) | $(@{Tag=Longview clients; Method=POST; Path=/v4/longview/clients; OperationId=post-longview-client; Summary=Create a Longview client; Deprecated=}.OperationId) | Create a Longview client |
| DELETE | $(@{Tag=Longview clients; Method=DELETE; Path=/v4/longview/clients/{clientId}; OperationId=delete-longview-client; Summary=Delete a Longview client; Deprecated=}.Path) | $(@{Tag=Longview clients; Method=DELETE; Path=/v4/longview/clients/{clientId}; OperationId=delete-longview-client; Summary=Delete a Longview client; Deprecated=}.OperationId) | Delete a Longview client |
| GET | $(@{Tag=Longview clients; Method=GET; Path=/v4/longview/clients/{clientId}; OperationId=get-longview-client; Summary=Get a Longview client; Deprecated=}.Path) | $(@{Tag=Longview clients; Method=GET; Path=/v4/longview/clients/{clientId}; OperationId=get-longview-client; Summary=Get a Longview client; Deprecated=}.OperationId) | Get a Longview client |
| PUT | $(@{Tag=Longview clients; Method=PUT; Path=/v4/longview/clients/{clientId}; OperationId=put-longview-client; Summary=Update a Longview client; Deprecated=}.Path) | $(@{Tag=Longview clients; Method=PUT; Path=/v4/longview/clients/{clientId}; OperationId=put-longview-client; Summary=Update a Longview client; Deprecated=}.OperationId) | Update a Longview client |

## Longview plans

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Longview plans; Method=GET; Path=/v4/longview/plan; OperationId=get-longview-plan; Summary=Get a Longview plan; Deprecated=}.Path) | $(@{Tag=Longview plans; Method=GET; Path=/v4/longview/plan; OperationId=get-longview-plan; Summary=Get a Longview plan; Deprecated=}.OperationId) | Get a Longview plan |
| PUT | $(@{Tag=Longview plans; Method=PUT; Path=/v4/longview/plan; OperationId=put-longview-plan; Summary=Update a Longview plan; Deprecated=}.Path) | $(@{Tag=Longview plans; Method=PUT; Path=/v4/longview/plan; OperationId=put-longview-plan; Summary=Update a Longview plan; Deprecated=}.OperationId) | Update a Longview plan |

## Longview subscriptions

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Longview subscriptions; Method=GET; Path=/v4/longview/subscriptions; OperationId=get-longview-subscriptions; Summary=List Longview subscriptions; Deprecated=}.Path) | $(@{Tag=Longview subscriptions; Method=GET; Path=/v4/longview/subscriptions; OperationId=get-longview-subscriptions; Summary=List Longview subscriptions; Deprecated=}.OperationId) | List Longview subscriptions |
| GET | $(@{Tag=Longview subscriptions; Method=GET; Path=/v4/longview/subscriptions/{subscriptionId}; OperationId=get-longview-subscription; Summary=Get a Longview subscription; Deprecated=}.Path) | $(@{Tag=Longview subscriptions; Method=GET; Path=/v4/longview/subscriptions/{subscriptionId}; OperationId=get-longview-subscription; Summary=Get a Longview subscription; Deprecated=}.OperationId) | Get a Longview subscription |

## Longview types

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Longview types; Method=GET; Path=/v4/longview/types; OperationId=get-longview-types; Summary=List Longview types; Deprecated=}.Path) | $(@{Tag=Longview types; Method=GET; Path=/v4/longview/types; OperationId=get-longview-types; Summary=List Longview types; Deprecated=}.OperationId) | List Longview types |

## Maintenance policies

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Maintenance policies; Method=GET; Path=/v4/maintenance/policies; OperationId=get-maintenance-policies; Summary=List maintenance policies; Deprecated=}.Path) | $(@{Tag=Maintenance policies; Method=GET; Path=/v4/maintenance/policies; OperationId=get-maintenance-policies; Summary=List maintenance policies; Deprecated=}.OperationId) | List maintenance policies |

## Maintenances

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Maintenances; Method=GET; Path=/v4/account/maintenance; OperationId=get-maintenance; Summary=List maintenances; Deprecated=}.Path) | $(@{Tag=Maintenances; Method=GET; Path=/v4/account/maintenance; OperationId=get-maintenance; Summary=List maintenances; Deprecated=}.OperationId) | List maintenances |

## Manage Beta programs

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Manage Beta programs; Method=GET; Path=/v4/account/betas; OperationId=get-enrolled-beta-programs; Summary=List enrolled Beta programs; Deprecated=}.Path) | $(@{Tag=Manage Beta programs; Method=GET; Path=/v4/account/betas; OperationId=get-enrolled-beta-programs; Summary=List enrolled Beta programs; Deprecated=}.OperationId) | List enrolled Beta programs |
| POST | $(@{Tag=Manage Beta programs; Method=POST; Path=/v4/account/betas; OperationId=post-beta-program; Summary=Enroll in a Beta program; Deprecated=}.Path) | $(@{Tag=Manage Beta programs; Method=POST; Path=/v4/account/betas; OperationId=post-beta-program; Summary=Enroll in a Beta program; Deprecated=}.OperationId) | Enroll in a Beta program |
| GET | $(@{Tag=Manage Beta programs; Method=GET; Path=/v4/account/betas/{betaId}; OperationId=get-enrolled-beta-program; Summary=Get an enrolled Beta program; Deprecated=}.Path) | $(@{Tag=Manage Beta programs; Method=GET; Path=/v4/account/betas/{betaId}; OperationId=get-enrolled-beta-program; Summary=Get an enrolled Beta program; Deprecated=}.OperationId) | Get an enrolled Beta program |

## Managed contacts

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Managed contacts; Method=GET; Path=/v4/managed/contacts; OperationId=get-managed-contacts; Summary=List managed contacts; Deprecated=}.Path) | $(@{Tag=Managed contacts; Method=GET; Path=/v4/managed/contacts; OperationId=get-managed-contacts; Summary=List managed contacts; Deprecated=}.OperationId) | List managed contacts |
| POST | $(@{Tag=Managed contacts; Method=POST; Path=/v4/managed/contacts; OperationId=post-managed-contact; Summary=Create a managed contact; Deprecated=}.Path) | $(@{Tag=Managed contacts; Method=POST; Path=/v4/managed/contacts; OperationId=post-managed-contact; Summary=Create a managed contact; Deprecated=}.OperationId) | Create a managed contact |
| DELETE | $(@{Tag=Managed contacts; Method=DELETE; Path=/v4/managed/contacts/{contactId}; OperationId=delete-managed-contact; Summary=Delete a managed contact; Deprecated=}.Path) | $(@{Tag=Managed contacts; Method=DELETE; Path=/v4/managed/contacts/{contactId}; OperationId=delete-managed-contact; Summary=Delete a managed contact; Deprecated=}.OperationId) | Delete a managed contact |
| GET | $(@{Tag=Managed contacts; Method=GET; Path=/v4/managed/contacts/{contactId}; OperationId=get-managed-contact; Summary=Get a managed contact; Deprecated=}.Path) | $(@{Tag=Managed contacts; Method=GET; Path=/v4/managed/contacts/{contactId}; OperationId=get-managed-contact; Summary=Get a managed contact; Deprecated=}.OperationId) | Get a managed contact |
| PUT | $(@{Tag=Managed contacts; Method=PUT; Path=/v4/managed/contacts/{contactId}; OperationId=put-managed-contact; Summary=Update a managed contact; Deprecated=}.Path) | $(@{Tag=Managed contacts; Method=PUT; Path=/v4/managed/contacts/{contactId}; OperationId=put-managed-contact; Summary=Update a managed contact; Deprecated=}.OperationId) | Update a managed contact |

## Managed credentials

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Managed credentials; Method=GET; Path=/v4/managed/credentials; OperationId=get-managed-credentials; Summary=List managed credentials; Deprecated=}.Path) | $(@{Tag=Managed credentials; Method=GET; Path=/v4/managed/credentials; OperationId=get-managed-credentials; Summary=List managed credentials; Deprecated=}.OperationId) | List managed credentials |
| POST | $(@{Tag=Managed credentials; Method=POST; Path=/v4/managed/credentials; OperationId=post-managed-credential; Summary=Create a managed credential; Deprecated=}.Path) | $(@{Tag=Managed credentials; Method=POST; Path=/v4/managed/credentials; OperationId=post-managed-credential; Summary=Create a managed credential; Deprecated=}.OperationId) | Create a managed credential |
| GET | $(@{Tag=Managed credentials; Method=GET; Path=/v4/managed/credentials/{credentialId}; OperationId=get-managed-credential; Summary=Get a managed credential; Deprecated=}.Path) | $(@{Tag=Managed credentials; Method=GET; Path=/v4/managed/credentials/{credentialId}; OperationId=get-managed-credential; Summary=Get a managed credential; Deprecated=}.OperationId) | Get a managed credential |
| PUT | $(@{Tag=Managed credentials; Method=PUT; Path=/v4/managed/credentials/{credentialId}; OperationId=put-managed-credential; Summary=Update a managed credential; Deprecated=}.Path) | $(@{Tag=Managed credentials; Method=PUT; Path=/v4/managed/credentials/{credentialId}; OperationId=put-managed-credential; Summary=Update a managed credential; Deprecated=}.OperationId) | Update a managed credential |
| POST | $(@{Tag=Managed credentials; Method=POST; Path=/v4/managed/credentials/{credentialId}/revoke; OperationId=post-managed-credential-revoke; Summary=Delete a managed credential; Deprecated=}.Path) | $(@{Tag=Managed credentials; Method=POST; Path=/v4/managed/credentials/{credentialId}/revoke; OperationId=post-managed-credential-revoke; Summary=Delete a managed credential; Deprecated=}.OperationId) | Delete a managed credential |
| POST | $(@{Tag=Managed credentials; Method=POST; Path=/v4/managed/credentials/{credentialId}/update; OperationId=post-managed-credential-username-password; Summary=Update a managed credential's username and password; Deprecated=}.Path) | $(@{Tag=Managed credentials; Method=POST; Path=/v4/managed/credentials/{credentialId}/update; OperationId=post-managed-credential-username-password; Summary=Update a managed credential's username and password; Deprecated=}.OperationId) | Update a managed credential's username and password |

## Managed issues

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Managed issues; Method=GET; Path=/v4/managed/issues; OperationId=get-managed-issues; Summary=List managed issues; Deprecated=}.Path) | $(@{Tag=Managed issues; Method=GET; Path=/v4/managed/issues; OperationId=get-managed-issues; Summary=List managed issues; Deprecated=}.OperationId) | List managed issues |
| GET | $(@{Tag=Managed issues; Method=GET; Path=/v4/managed/issues/{issueId}; OperationId=get-managed-issue; Summary=Get a managed issue; Deprecated=}.Path) | $(@{Tag=Managed issues; Method=GET; Path=/v4/managed/issues/{issueId}; OperationId=get-managed-issue; Summary=Get a managed issue; Deprecated=}.OperationId) | Get a managed issue |

## Managed Linode settings

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Managed Linode settings; Method=GET; Path=/v4/managed/linode-settings; OperationId=get-managed-linode-settings; Summary=List managed Linode settings; Deprecated=}.Path) | $(@{Tag=Managed Linode settings; Method=GET; Path=/v4/managed/linode-settings; OperationId=get-managed-linode-settings; Summary=List managed Linode settings; Deprecated=}.OperationId) | List managed Linode settings |
| GET | $(@{Tag=Managed Linode settings; Method=GET; Path=/v4/managed/linode-settings/{linodeId}; OperationId=get-managed-linode-setting; Summary=Get a Linode's managed settings; Deprecated=}.Path) | $(@{Tag=Managed Linode settings; Method=GET; Path=/v4/managed/linode-settings/{linodeId}; OperationId=get-managed-linode-setting; Summary=Get a Linode's managed settings; Deprecated=}.OperationId) | Get a Linode's managed settings |
| PUT | $(@{Tag=Managed Linode settings; Method=PUT; Path=/v4/managed/linode-settings/{linodeId}; OperationId=put-managed-linode-setting; Summary=Update a Linode's managed settings; Deprecated=}.Path) | $(@{Tag=Managed Linode settings; Method=PUT; Path=/v4/managed/linode-settings/{linodeId}; OperationId=put-managed-linode-setting; Summary=Update a Linode's managed settings; Deprecated=}.OperationId) | Update a Linode's managed settings |

## Managed service monitors

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Managed service monitors; Method=GET; Path=/v4/managed/services; OperationId=get-managed-services; Summary=List managed services; Deprecated=}.Path) | $(@{Tag=Managed service monitors; Method=GET; Path=/v4/managed/services; OperationId=get-managed-services; Summary=List managed services; Deprecated=}.OperationId) | List managed services |
| POST | $(@{Tag=Managed service monitors; Method=POST; Path=/v4/managed/services; OperationId=post-managed-service; Summary=Create a managed service; Deprecated=}.Path) | $(@{Tag=Managed service monitors; Method=POST; Path=/v4/managed/services; OperationId=post-managed-service; Summary=Create a managed service; Deprecated=}.OperationId) | Create a managed service |
| DELETE | $(@{Tag=Managed service monitors; Method=DELETE; Path=/v4/managed/services/{serviceId}; OperationId=delete-managed-service; Summary=Delete a managed service monitor; Deprecated=}.Path) | $(@{Tag=Managed service monitors; Method=DELETE; Path=/v4/managed/services/{serviceId}; OperationId=delete-managed-service; Summary=Delete a managed service monitor; Deprecated=}.OperationId) | Delete a managed service monitor |
| GET | $(@{Tag=Managed service monitors; Method=GET; Path=/v4/managed/services/{serviceId}; OperationId=get-managed-service; Summary=Get a managed service monitor; Deprecated=}.Path) | $(@{Tag=Managed service monitors; Method=GET; Path=/v4/managed/services/{serviceId}; OperationId=get-managed-service; Summary=Get a managed service monitor; Deprecated=}.OperationId) | Get a managed service monitor |
| PUT | $(@{Tag=Managed service monitors; Method=PUT; Path=/v4/managed/services/{serviceId}; OperationId=put-managed-service; Summary=Update a managed service monitor; Deprecated=}.Path) | $(@{Tag=Managed service monitors; Method=PUT; Path=/v4/managed/services/{serviceId}; OperationId=put-managed-service; Summary=Update a managed service monitor; Deprecated=}.OperationId) | Update a managed service monitor |
| POST | $(@{Tag=Managed service monitors; Method=POST; Path=/v4/managed/services/{serviceId}/disable; OperationId=post-disable-managed-service; Summary=Disable a managed service monitor; Deprecated=}.Path) | $(@{Tag=Managed service monitors; Method=POST; Path=/v4/managed/services/{serviceId}/disable; OperationId=post-disable-managed-service; Summary=Disable a managed service monitor; Deprecated=}.OperationId) | Disable a managed service monitor |
| POST | $(@{Tag=Managed service monitors; Method=POST; Path=/v4/managed/services/{serviceId}/enable; OperationId=post-enable-managed-service; Summary=Enable a managed service monitor; Deprecated=}.Path) | $(@{Tag=Managed service monitors; Method=POST; Path=/v4/managed/services/{serviceId}/enable; OperationId=post-enable-managed-service; Summary=Enable a managed service monitor; Deprecated=}.OperationId) | Enable a managed service monitor |

## Managed SSH keys

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Managed SSH keys; Method=GET; Path=/v4/managed/credentials/sshkey; OperationId=get-managed-ssh-key; Summary=Get a managed SSH key; Deprecated=}.Path) | $(@{Tag=Managed SSH keys; Method=GET; Path=/v4/managed/credentials/sshkey; OperationId=get-managed-ssh-key; Summary=Get a managed SSH key; Deprecated=}.OperationId) | Get a managed SSH key |

## Managed statistics

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Managed statistics; Method=GET; Path=/v4/managed/stats; OperationId=get-managed-stats; Summary=List managed stats; Deprecated=}.Path) | $(@{Tag=Managed statistics; Method=GET; Path=/v4/managed/stats; OperationId=get-managed-stats; Summary=List managed stats; Deprecated=}.OperationId) | List managed stats |

## Metrics

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/dashboards; OperationId=get-dashboards-all; Summary=List dashboards; Deprecated=}.Path) | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/dashboards; OperationId=get-dashboards-all; Summary=List dashboards; Deprecated=}.OperationId) | List dashboards |
| GET | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/dashboards/{dashboardId}; OperationId=get-dashboards-by-id; Summary=Get a dashboard; Deprecated=}.Path) | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/dashboards/{dashboardId}; OperationId=get-dashboards-by-id; Summary=Get a dashboard; Deprecated=}.OperationId) | Get a dashboard |
| GET | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services; OperationId=get-monitor-services; Summary=List supported service types; Deprecated=}.Path) | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services; OperationId=get-monitor-services; Summary=List supported service types; Deprecated=}.OperationId) | List supported service types |
| GET | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services/{serviceType}; OperationId=get-monitor-services-for-service-type; Summary=Get details for a supported service type; Deprecated=}.Path) | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services/{serviceType}; OperationId=get-monitor-services-for-service-type; Summary=Get details for a supported service type; Deprecated=}.OperationId) | Get details for a supported service type |
| GET | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services/{serviceType}/dashboards; OperationId=get-dashboards; Summary=List dashboards for a service type; Deprecated=}.Path) | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services/{serviceType}/dashboards; OperationId=get-dashboards; Summary=List dashboards for a service type; Deprecated=}.OperationId) | List dashboards for a service type |
| GET | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services/{serviceType}/metric-definitions; OperationId=get-monitor-information; Summary=List metrics for a service type; Deprecated=}.Path) | $(@{Tag=Metrics; Method=GET; Path=/v4/monitor/services/{serviceType}/metric-definitions; OperationId=get-monitor-information; Summary=List metrics for a service type; Deprecated=}.OperationId) | List metrics for a service type |
| POST | $(@{Tag=Metrics; Method=POST; Path=/v4/monitor/services/{serviceType}/metrics; OperationId=post-read-metric; Summary=Get an entity's metrics; Deprecated=}.Path) | $(@{Tag=Metrics; Method=POST; Path=/v4/monitor/services/{serviceType}/metrics; OperationId=post-read-metric; Summary=Get an entity's metrics; Deprecated=}.OperationId) | Get an entity's metrics |
| POST | $(@{Tag=Metrics; Method=POST; Path=/v4/monitor/services/{serviceType}/token; OperationId=post-get-token; Summary=Create a token for a service type; Deprecated=}.Path) | $(@{Tag=Metrics; Method=POST; Path=/v4/monitor/services/{serviceType}/token; OperationId=post-get-token; Summary=Create a token for a service type; Deprecated=}.OperationId) | Create a token for a service type |

## MySQL

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/config; OperationId=get-databases-mysql-config; Summary=List MySQL Managed Database advanced parameters; Deprecated=}.Path) | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/config; OperationId=get-databases-mysql-config; Summary=List MySQL Managed Database advanced parameters; Deprecated=}.OperationId) | List MySQL Managed Database advanced parameters |
| GET | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances; OperationId=get-databases-mysql-instances; Summary=List MySQL Managed Databases; Deprecated=}.Path) | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances; OperationId=get-databases-mysql-instances; Summary=List MySQL Managed Databases; Deprecated=}.OperationId) | List MySQL Managed Databases |
| POST | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances; OperationId=post-databases-mysql-instances; Summary=Create or restore a MySQL Managed Database; Deprecated=}.Path) | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances; OperationId=post-databases-mysql-instances; Summary=Create or restore a MySQL Managed Database; Deprecated=}.OperationId) | Create or restore a MySQL Managed Database |
| DELETE | $(@{Tag=MySQL; Method=DELETE; Path=/v4/databases/mysql/instances/{mysqlInstanceId}; OperationId=delete-databases-mysql-instance; Summary=Delete a MySQL Managed Database; Deprecated=}.Path) | $(@{Tag=MySQL; Method=DELETE; Path=/v4/databases/mysql/instances/{mysqlInstanceId}; OperationId=delete-databases-mysql-instance; Summary=Delete a MySQL Managed Database; Deprecated=}.OperationId) | Delete a MySQL Managed Database |
| GET | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances/{mysqlInstanceId}; OperationId=get-databases-mysql-instance; Summary=Get a MySQL Managed Database; Deprecated=}.Path) | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances/{mysqlInstanceId}; OperationId=get-databases-mysql-instance; Summary=Get a MySQL Managed Database; Deprecated=}.OperationId) | Get a MySQL Managed Database |
| PUT | $(@{Tag=MySQL; Method=PUT; Path=/v4/databases/mysql/instances/{mysqlInstanceId}; OperationId=put-databases-mysql-instance; Summary=Update a MySQL Managed Database; Deprecated=}.Path) | $(@{Tag=MySQL; Method=PUT; Path=/v4/databases/mysql/instances/{mysqlInstanceId}; OperationId=put-databases-mysql-instance; Summary=Update a MySQL Managed Database; Deprecated=}.OperationId) | Update a MySQL Managed Database |
| GET | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/credentials; OperationId=get-databases-mysql-instance-credentials; Summary=Get MySQL Managed Database credentials; Deprecated=}.Path) | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/credentials; OperationId=get-databases-mysql-instance-credentials; Summary=Get MySQL Managed Database credentials; Deprecated=}.OperationId) | Get MySQL Managed Database credentials |
| POST | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/credentials/reset; OperationId=post-databases-mysql-instance-credentials-reset; Summary=Reset MySQL Managed Database credentials; Deprecated=}.Path) | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/credentials/reset; OperationId=post-databases-mysql-instance-credentials-reset; Summary=Reset MySQL Managed Database credentials; Deprecated=}.OperationId) | Reset MySQL Managed Database credentials |
| POST | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/patch; OperationId=post-databases-mysql-instance-patch; Summary=Patch a MySQL Managed Database; Deprecated=}.Path) | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/patch; OperationId=post-databases-mysql-instance-patch; Summary=Patch a MySQL Managed Database; Deprecated=}.OperationId) | Patch a MySQL Managed Database |
| POST | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/resume; OperationId=resume-databases-mysql-instance; Summary=Resume a MySQL Managed Database; Deprecated=}.Path) | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/resume; OperationId=resume-databases-mysql-instance; Summary=Resume a MySQL Managed Database; Deprecated=}.OperationId) | Resume a MySQL Managed Database |
| GET | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/ssl; OperationId=get-databases-mysql-instance-ssl; Summary=Get a MySQL Managed Database SSL certificate; Deprecated=}.Path) | $(@{Tag=MySQL; Method=GET; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/ssl; OperationId=get-databases-mysql-instance-ssl; Summary=Get a MySQL Managed Database SSL certificate; Deprecated=}.OperationId) | Get a MySQL Managed Database SSL certificate |
| POST | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/suspend; OperationId=suspend-databases-mysql-instance; Summary=Suspend a MySQL Managed Database; Deprecated=}.Path) | $(@{Tag=MySQL; Method=POST; Path=/v4/databases/mysql/instances/{mysqlInstanceId}/suspend; OperationId=suspend-databases-mysql-instance; Summary=Suspend a MySQL Managed Database; Deprecated=}.OperationId) | Suspend a MySQL Managed Database |

## Network transfer prices

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Network transfer prices; Method=GET; Path=/v4/network-transfer/prices; OperationId=get-network-transfer-prices; Summary=List network transfer prices; Deprecated=}.Path) | $(@{Tag=Network transfer prices; Method=GET; Path=/v4/network-transfer/prices; OperationId=get-network-transfer-prices; Summary=List network transfer prices; Deprecated=}.OperationId) | List network transfer prices |

## NodeBalancer types

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=NodeBalancer types; Method=GET; Path=/v4/nodebalancers/types; OperationId=get-node-balancer-types; Summary=List NodeBalancer types; Deprecated=}.Path) | $(@{Tag=NodeBalancer types; Method=GET; Path=/v4/nodebalancers/types; OperationId=get-node-balancer-types; Summary=List NodeBalancer types; Deprecated=}.OperationId) | List NodeBalancer types |

## NodeBalancers

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=NodeBalancers; Method=GET; Path=/v4/nodebalancers; OperationId=get-node-balancers; Summary=List NodeBalancers; Deprecated=}.Path) | $(@{Tag=NodeBalancers; Method=GET; Path=/v4/nodebalancers; OperationId=get-node-balancers; Summary=List NodeBalancers; Deprecated=}.OperationId) | List NodeBalancers |
| POST | $(@{Tag=NodeBalancers; Method=POST; Path=/v4/nodebalancers; OperationId=post-node-balancer; Summary=Create a NodeBalancer; Deprecated=}.Path) | $(@{Tag=NodeBalancers; Method=POST; Path=/v4/nodebalancers; OperationId=post-node-balancer; Summary=Create a NodeBalancer; Deprecated=}.OperationId) | Create a NodeBalancer |
| DELETE | $(@{Tag=NodeBalancers; Method=DELETE; Path=/v4/nodebalancers/{nodeBalancerId}; OperationId=delete-node-balancer; Summary=Delete a NodeBalancer; Deprecated=}.Path) | $(@{Tag=NodeBalancers; Method=DELETE; Path=/v4/nodebalancers/{nodeBalancerId}; OperationId=delete-node-balancer; Summary=Delete a NodeBalancer; Deprecated=}.OperationId) | Delete a NodeBalancer |
| GET | $(@{Tag=NodeBalancers; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}; OperationId=get-node-balancer; Summary=Get a NodeBalancer; Deprecated=}.Path) | $(@{Tag=NodeBalancers; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}; OperationId=get-node-balancer; Summary=Get a NodeBalancer; Deprecated=}.OperationId) | Get a NodeBalancer |
| PUT | $(@{Tag=NodeBalancers; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}; OperationId=put-node-balancer; Summary=Update a NodeBalancer; Deprecated=}.Path) | $(@{Tag=NodeBalancers; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}; OperationId=put-node-balancer; Summary=Update a NodeBalancer; Deprecated=}.OperationId) | Update a NodeBalancer |

## Nodes

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Nodes; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes; OperationId=get-node-balancer-config-nodes; Summary=List nodes; Deprecated=}.Path) | $(@{Tag=Nodes; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes; OperationId=get-node-balancer-config-nodes; Summary=List nodes; Deprecated=}.OperationId) | List nodes |
| POST | $(@{Tag=Nodes; Method=POST; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes; OperationId=post-node-balancer-node; Summary=Create a node; Deprecated=}.Path) | $(@{Tag=Nodes; Method=POST; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes; OperationId=post-node-balancer-node; Summary=Create a node; Deprecated=}.OperationId) | Create a node |
| DELETE | $(@{Tag=Nodes; Method=DELETE; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes/{nodeId}; OperationId=delete-node-balancer-config-node; Summary=Delete a NodeBalancer's node; Deprecated=}.Path) | $(@{Tag=Nodes; Method=DELETE; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes/{nodeId}; OperationId=delete-node-balancer-config-node; Summary=Delete a NodeBalancer's node; Deprecated=}.OperationId) | Delete a NodeBalancer's node |
| GET | $(@{Tag=Nodes; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes/{nodeId}; OperationId=get-node-balancer-node; Summary=Get a NodeBalancer's node; Deprecated=}.Path) | $(@{Tag=Nodes; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes/{nodeId}; OperationId=get-node-balancer-node; Summary=Get a NodeBalancer's node; Deprecated=}.OperationId) | Get a NodeBalancer's node |
| PUT | $(@{Tag=Nodes; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes/{nodeId}; OperationId=put-node-balancer-node; Summary=Update a node; Deprecated=}.Path) | $(@{Tag=Nodes; Method=PUT; Path=/v4/nodebalancers/{nodeBalancerId}/configs/{configId}/nodes/{nodeId}; OperationId=put-node-balancer-node; Summary=Update a node; Deprecated=}.OperationId) | Update a node |

## Notifications

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Notifications; Method=GET; Path=/v4/account/notifications; OperationId=get-notifications; Summary=List notifications; Deprecated=}.Path) | $(@{Tag=Notifications; Method=GET; Path=/v4/account/notifications; OperationId=get-notifications; Summary=List notifications; Deprecated=}.OperationId) | List notifications |

## OAuth apps

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=OAuth apps; Method=GET; Path=/v4/profile/apps; OperationId=get-profile-apps; Summary=List authorized apps; Deprecated=}.Path) | $(@{Tag=OAuth apps; Method=GET; Path=/v4/profile/apps; OperationId=get-profile-apps; Summary=List authorized apps; Deprecated=}.OperationId) | List authorized apps |
| DELETE | $(@{Tag=OAuth apps; Method=DELETE; Path=/v4/profile/apps/{appId}; OperationId=delete-profile-app; Summary=Revoke app access; Deprecated=}.Path) | $(@{Tag=OAuth apps; Method=DELETE; Path=/v4/profile/apps/{appId}; OperationId=delete-profile-app; Summary=Revoke app access; Deprecated=}.OperationId) | Revoke app access |
| GET | $(@{Tag=OAuth apps; Method=GET; Path=/v4/profile/apps/{appId}; OperationId=get-profile-app; Summary=Get an authorized app; Deprecated=}.Path) | $(@{Tag=OAuth apps; Method=GET; Path=/v4/profile/apps/{appId}; OperationId=get-profile-app; Summary=Get an authorized app; Deprecated=}.OperationId) | Get an authorized app |

## OAuth clients

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=OAuth clients; Method=GET; Path=/v4/account/oauth-clients; OperationId=get-clients; Summary=List OAuth clients; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=GET; Path=/v4/account/oauth-clients; OperationId=get-clients; Summary=List OAuth clients; Deprecated=}.OperationId) | List OAuth clients |
| POST | $(@{Tag=OAuth clients; Method=POST; Path=/v4/account/oauth-clients; OperationId=post-client; Summary=Create an OAuth client; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=POST; Path=/v4/account/oauth-clients; OperationId=post-client; Summary=Create an OAuth client; Deprecated=}.OperationId) | Create an OAuth client |
| DELETE | $(@{Tag=OAuth clients; Method=DELETE; Path=/v4/account/oauth-clients/{clientId}; OperationId=delete-client; Summary=Delete an OAuth client; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=DELETE; Path=/v4/account/oauth-clients/{clientId}; OperationId=delete-client; Summary=Delete an OAuth client; Deprecated=}.OperationId) | Delete an OAuth client |
| GET | $(@{Tag=OAuth clients; Method=GET; Path=/v4/account/oauth-clients/{clientId}; OperationId=get-client; Summary=Get an OAuth client; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=GET; Path=/v4/account/oauth-clients/{clientId}; OperationId=get-client; Summary=Get an OAuth client; Deprecated=}.OperationId) | Get an OAuth client |
| PUT | $(@{Tag=OAuth clients; Method=PUT; Path=/v4/account/oauth-clients/{clientId}; OperationId=put-client; Summary=Update an OAuth client; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=PUT; Path=/v4/account/oauth-clients/{clientId}; OperationId=put-client; Summary=Update an OAuth client; Deprecated=}.OperationId) | Update an OAuth client |
| POST | $(@{Tag=OAuth clients; Method=POST; Path=/v4/account/oauth-clients/{clientId}/reset-secret; OperationId=post-reset-client-secret; Summary=Reset an OAuth client secret; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=POST; Path=/v4/account/oauth-clients/{clientId}/reset-secret; OperationId=post-reset-client-secret; Summary=Reset an OAuth client secret; Deprecated=}.OperationId) | Reset an OAuth client secret |
| GET | $(@{Tag=OAuth clients; Method=GET; Path=/v4/account/oauth-clients/{clientId}/thumbnail; OperationId=get-client-thumbnail; Summary=Get an OAuth client's thumbnail; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=GET; Path=/v4/account/oauth-clients/{clientId}/thumbnail; OperationId=get-client-thumbnail; Summary=Get an OAuth client's thumbnail; Deprecated=}.OperationId) | Get an OAuth client's thumbnail |
| PUT | $(@{Tag=OAuth clients; Method=PUT; Path=/v4/account/oauth-clients/{clientId}/thumbnail; OperationId=put-client-thumbnail; Summary=Update an OAuth client's thumbnail; Deprecated=}.Path) | $(@{Tag=OAuth clients; Method=PUT; Path=/v4/account/oauth-clients/{clientId}/thumbnail; OperationId=put-client-thumbnail; Summary=Update an OAuth client's thumbnail; Deprecated=}.OperationId) | Update an OAuth client's thumbnail |

## OAuth preferences

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=OAuth preferences; Method=GET; Path=/v4/profile/preferences; OperationId=get-user-preferences; Summary=Get user OAuth preferences; Deprecated=}.Path) | $(@{Tag=OAuth preferences; Method=GET; Path=/v4/profile/preferences; OperationId=get-user-preferences; Summary=Get user OAuth preferences; Deprecated=}.OperationId) | Get user OAuth preferences |
| PUT | $(@{Tag=OAuth preferences; Method=PUT; Path=/v4/profile/preferences; OperationId=put-user-preferences; Summary=Update a user's OAuth preferences; Deprecated=}.Path) | $(@{Tag=OAuth preferences; Method=PUT; Path=/v4/profile/preferences; OperationId=put-user-preferences; Summary=Update a user's OAuth preferences; Deprecated=}.OperationId) | Update a user's OAuth preferences |

## OBJ clusters

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=OBJ clusters; Method=GET; Path=/v4/object-storage/clusters; OperationId=get-object-storage-clusters; Summary=List clusters; Deprecated=yes}.Path) | $(@{Tag=OBJ clusters; Method=GET; Path=/v4/object-storage/clusters; OperationId=get-object-storage-clusters; Summary=List clusters; Deprecated=yes}.OperationId) | [DEPRECATED] List clusters |

## Object Storage

| Method | Path | operationId | Summary |
|---|---|---|---|
| POST | $(@{Tag=Object Storage; Method=POST; Path=/v4/object-storage/cancel; OperationId=post-cancel-object-storage; Summary=Cancel Object Storage; Deprecated=}.Path) | $(@{Tag=Object Storage; Method=POST; Path=/v4/object-storage/cancel; OperationId=post-cancel-object-storage; Summary=Cancel Object Storage; Deprecated=}.OperationId) | Cancel Object Storage |
| GET | $(@{Tag=Object Storage; Method=GET; Path=/v4/object-storage/transfer; OperationId=get-object-storage-transfer; Summary=Get Object Storage transfer data; Deprecated=}.Path) | $(@{Tag=Object Storage; Method=GET; Path=/v4/object-storage/transfer; OperationId=get-object-storage-transfer; Summary=Get Object Storage transfer data; Deprecated=}.OperationId) | Get Object Storage transfer data |
| GET | $(@{Tag=Object Storage; Method=GET; Path=/v4/object-storage/types; OperationId=get-object-storage-types; Summary=List Object Storage types; Deprecated=}.Path) | $(@{Tag=Object Storage; Method=GET; Path=/v4/object-storage/types; OperationId=get-object-storage-types; Summary=List Object Storage types; Deprecated=}.OperationId) | List Object Storage types |

## Partner referrals

| Method | Path | operationId | Summary |
|---|---|---|---|
| POST | $(@{Tag=Partner referrals; Method=POST; Path=/v4/marketplace; OperationId=post-marketplace-contact; Summary=Create a Marketplace third-party referral; Deprecated=}.Path) | $(@{Tag=Partner referrals; Method=POST; Path=/v4/marketplace; OperationId=post-marketplace-contact; Summary=Create a Marketplace third-party referral; Deprecated=}.OperationId) | Create a Marketplace third-party referral |

## Payment methods

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Payment methods; Method=GET; Path=/v4/account/payment-methods; OperationId=get-payment-methods; Summary=List payment methods; Deprecated=}.Path) | $(@{Tag=Payment methods; Method=GET; Path=/v4/account/payment-methods; OperationId=get-payment-methods; Summary=List payment methods; Deprecated=}.OperationId) | List payment methods |
| POST | $(@{Tag=Payment methods; Method=POST; Path=/v4/account/payment-methods; OperationId=post-payment-method; Summary=Add a payment method; Deprecated=}.Path) | $(@{Tag=Payment methods; Method=POST; Path=/v4/account/payment-methods; OperationId=post-payment-method; Summary=Add a payment method; Deprecated=}.OperationId) | Add a payment method |
| DELETE | $(@{Tag=Payment methods; Method=DELETE; Path=/v4/account/payment-methods/{paymentMethodId}; OperationId=delete-payment-method; Summary=Delete a payment method; Deprecated=}.Path) | $(@{Tag=Payment methods; Method=DELETE; Path=/v4/account/payment-methods/{paymentMethodId}; OperationId=delete-payment-method; Summary=Delete a payment method; Deprecated=}.OperationId) | Delete a payment method |
| GET | $(@{Tag=Payment methods; Method=GET; Path=/v4/account/payment-methods/{paymentMethodId}; OperationId=get-payment-method; Summary=Get a payment method; Deprecated=}.Path) | $(@{Tag=Payment methods; Method=GET; Path=/v4/account/payment-methods/{paymentMethodId}; OperationId=get-payment-method; Summary=Get a payment method; Deprecated=}.OperationId) | Get a payment method |
| POST | $(@{Tag=Payment methods; Method=POST; Path=/v4/account/payment-methods/{paymentMethodId}/make-default; OperationId=post-make-payment-method-default; Summary=Set a default payment method; Deprecated=}.Path) | $(@{Tag=Payment methods; Method=POST; Path=/v4/account/payment-methods/{paymentMethodId}/make-default; OperationId=post-make-payment-method-default; Summary=Set a default payment method; Deprecated=}.OperationId) | Set a default payment method |

## Payments

| Method | Path | operationId | Summary |
|---|---|---|---|
| POST | $(@{Tag=Payments; Method=POST; Path=/v4/account/credit-card; OperationId=post-credit-card; Summary=Add or edit a credit card; Deprecated=yes}.Path) | $(@{Tag=Payments; Method=POST; Path=/v4/account/credit-card; OperationId=post-credit-card; Summary=Add or edit a credit card; Deprecated=yes}.OperationId) | [DEPRECATED] Add or edit a credit card |
| GET | $(@{Tag=Payments; Method=GET; Path=/v4/account/payments; OperationId=get-payments; Summary=List payments; Deprecated=}.Path) | $(@{Tag=Payments; Method=GET; Path=/v4/account/payments; OperationId=get-payments; Summary=List payments; Deprecated=}.OperationId) | List payments |
| POST | $(@{Tag=Payments; Method=POST; Path=/v4/account/payments; OperationId=post-payment; Summary=Make a payment; Deprecated=}.Path) | $(@{Tag=Payments; Method=POST; Path=/v4/account/payments; OperationId=post-payment; Summary=Make a payment; Deprecated=}.OperationId) | Make a payment |
| GET | $(@{Tag=Payments; Method=GET; Path=/v4/account/payments/{paymentId}; OperationId=get-payment; Summary=Get a payment; Deprecated=}.Path) | $(@{Tag=Payments; Method=GET; Path=/v4/account/payments/{paymentId}; OperationId=get-payment; Summary=Get a payment; Deprecated=}.OperationId) | Get a payment |
| POST | $(@{Tag=Payments; Method=POST; Path=/v4/account/payments/paypal; OperationId=post-pay-pal-payment; Summary=Stage a PayPal payment; Deprecated=yes}.Path) | $(@{Tag=Payments; Method=POST; Path=/v4/account/payments/paypal; OperationId=post-pay-pal-payment; Summary=Stage a PayPal payment; Deprecated=yes}.OperationId) | [DEPRECATED] Stage a PayPal payment |
| POST | $(@{Tag=Payments; Method=POST; Path=/v4/account/payments/paypal/execute; OperationId=post-execute-pay-pal-payment; Summary=Execute a PayPal payment; Deprecated=yes}.Path) | $(@{Tag=Payments; Method=POST; Path=/v4/account/payments/paypal/execute; OperationId=post-execute-pay-pal-payment; Summary=Execute a PayPal payment; Deprecated=yes}.OperationId) | [DEPRECATED] Execute a PayPal payment |

## Personal access tokens

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Personal access tokens; Method=GET; Path=/v4/profile/tokens; OperationId=get-personal-access-tokens; Summary=List personal access tokens; Deprecated=}.Path) | $(@{Tag=Personal access tokens; Method=GET; Path=/v4/profile/tokens; OperationId=get-personal-access-tokens; Summary=List personal access tokens; Deprecated=}.OperationId) | List personal access tokens |
| POST | $(@{Tag=Personal access tokens; Method=POST; Path=/v4/profile/tokens; OperationId=post-personal-access-token; Summary=Create a personal access token; Deprecated=}.Path) | $(@{Tag=Personal access tokens; Method=POST; Path=/v4/profile/tokens; OperationId=post-personal-access-token; Summary=Create a personal access token; Deprecated=}.OperationId) | Create a personal access token |
| DELETE | $(@{Tag=Personal access tokens; Method=DELETE; Path=/v4/profile/tokens/{tokenId}; OperationId=delete-personal-access-token; Summary=Revoke a personal access token; Deprecated=}.Path) | $(@{Tag=Personal access tokens; Method=DELETE; Path=/v4/profile/tokens/{tokenId}; OperationId=delete-personal-access-token; Summary=Revoke a personal access token; Deprecated=}.OperationId) | Revoke a personal access token |
| GET | $(@{Tag=Personal access tokens; Method=GET; Path=/v4/profile/tokens/{tokenId}; OperationId=get-personal-access-token; Summary=Get a personal access token; Deprecated=}.Path) | $(@{Tag=Personal access tokens; Method=GET; Path=/v4/profile/tokens/{tokenId}; OperationId=get-personal-access-token; Summary=Get a personal access token; Deprecated=}.OperationId) | Get a personal access token |
| PUT | $(@{Tag=Personal access tokens; Method=PUT; Path=/v4/profile/tokens/{tokenId}; OperationId=put-personal-access-token; Summary=Update a personal access token; Deprecated=}.Path) | $(@{Tag=Personal access tokens; Method=PUT; Path=/v4/profile/tokens/{tokenId}; OperationId=put-personal-access-token; Summary=Update a personal access token; Deprecated=}.OperationId) | Update a personal access token |

## Phone number

| Method | Path | operationId | Summary |
|---|---|---|---|
| DELETE | $(@{Tag=Phone number; Method=DELETE; Path=/v4/profile/phone-number; OperationId=delete-profile-phone-number; Summary=Delete a phone number; Deprecated=}.Path) | $(@{Tag=Phone number; Method=DELETE; Path=/v4/profile/phone-number; OperationId=delete-profile-phone-number; Summary=Delete a phone number; Deprecated=}.OperationId) | Delete a phone number |
| POST | $(@{Tag=Phone number; Method=POST; Path=/v4/profile/phone-number; OperationId=post-profile-phone-number; Summary=Send a phone number verification code; Deprecated=}.Path) | $(@{Tag=Phone number; Method=POST; Path=/v4/profile/phone-number; OperationId=post-profile-phone-number; Summary=Send a phone number verification code; Deprecated=}.OperationId) | Send a phone number verification code |
| POST | $(@{Tag=Phone number; Method=POST; Path=/v4/profile/phone-number/verify; OperationId=post-profile-phone-number-verify; Summary=Verify a phone number; Deprecated=}.Path) | $(@{Tag=Phone number; Method=POST; Path=/v4/profile/phone-number/verify; OperationId=post-profile-phone-number-verify; Summary=Verify a phone number; Deprecated=}.OperationId) | Verify a phone number |

## Placement groups

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Placement groups; Method=GET; Path=/v4/placement/groups; OperationId=get-placement-groups; Summary=List placement groups; Deprecated=}.Path) | $(@{Tag=Placement groups; Method=GET; Path=/v4/placement/groups; OperationId=get-placement-groups; Summary=List placement groups; Deprecated=}.OperationId) | List placement groups |
| POST | $(@{Tag=Placement groups; Method=POST; Path=/v4/placement/groups; OperationId=post-placement-group; Summary=Create a placement group; Deprecated=}.Path) | $(@{Tag=Placement groups; Method=POST; Path=/v4/placement/groups; OperationId=post-placement-group; Summary=Create a placement group; Deprecated=}.OperationId) | Create a placement group |
| DELETE | $(@{Tag=Placement groups; Method=DELETE; Path=/v4/placement/groups/{groupId}; OperationId=delete-placement-group; Summary=Delete a placement group; Deprecated=}.Path) | $(@{Tag=Placement groups; Method=DELETE; Path=/v4/placement/groups/{groupId}; OperationId=delete-placement-group; Summary=Delete a placement group; Deprecated=}.OperationId) | Delete a placement group |
| GET | $(@{Tag=Placement groups; Method=GET; Path=/v4/placement/groups/{groupId}; OperationId=get-placement-group; Summary=Get a placement group; Deprecated=}.Path) | $(@{Tag=Placement groups; Method=GET; Path=/v4/placement/groups/{groupId}; OperationId=get-placement-group; Summary=Get a placement group; Deprecated=}.OperationId) | Get a placement group |
| PUT | $(@{Tag=Placement groups; Method=PUT; Path=/v4/placement/groups/{groupId}; OperationId=put-placement-group; Summary=Update a placement group; Deprecated=}.Path) | $(@{Tag=Placement groups; Method=PUT; Path=/v4/placement/groups/{groupId}; OperationId=put-placement-group; Summary=Update a placement group; Deprecated=}.OperationId) | Update a placement group |
| POST | $(@{Tag=Placement groups; Method=POST; Path=/v4/placement/groups/{groupId}/assign; OperationId=post-group-add-linode; Summary=Assign a placement group; Deprecated=}.Path) | $(@{Tag=Placement groups; Method=POST; Path=/v4/placement/groups/{groupId}/assign; OperationId=post-group-add-linode; Summary=Assign a placement group; Deprecated=}.OperationId) | Assign a placement group |
| POST | $(@{Tag=Placement groups; Method=POST; Path=/v4/placement/groups/{groupId}/unassign; OperationId=post-group-unassign; Summary=Unassign a placement group; Deprecated=}.Path) | $(@{Tag=Placement groups; Method=POST; Path=/v4/placement/groups/{groupId}/unassign; OperationId=post-group-unassign; Summary=Unassign a placement group; Deprecated=}.OperationId) | Unassign a placement group |

## PostgreSQL

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/config; OperationId=get-databases-postgresql-config; Summary=List PostgreSQL Managed Database advanced parameters; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/config; OperationId=get-databases-postgresql-config; Summary=List PostgreSQL Managed Database advanced parameters; Deprecated=}.OperationId) | List PostgreSQL Managed Database advanced parameters |
| GET | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances; OperationId=get-databases-postgre-sql-instances; Summary=List PostgreSQL Managed Databases; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances; OperationId=get-databases-postgre-sql-instances; Summary=List PostgreSQL Managed Databases; Deprecated=}.OperationId) | List PostgreSQL Managed Databases |
| POST | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances; OperationId=post-databases-postgre-sql-instances; Summary=Create or restore a PostgreSQL Managed Database; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances; OperationId=post-databases-postgre-sql-instances; Summary=Create or restore a PostgreSQL Managed Database; Deprecated=}.OperationId) | Create or restore a PostgreSQL Managed Database |
| DELETE | $(@{Tag=PostgreSQL; Method=DELETE; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}; OperationId=delete-databases-postgre-sql-instance; Summary=Delete a PostgreSQL Managed Database; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=DELETE; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}; OperationId=delete-databases-postgre-sql-instance; Summary=Delete a PostgreSQL Managed Database; Deprecated=}.OperationId) | Delete a PostgreSQL Managed Database |
| GET | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}; OperationId=get-databases-postgre-sql-instance; Summary=Get a PostgreSQL Managed Database; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}; OperationId=get-databases-postgre-sql-instance; Summary=Get a PostgreSQL Managed Database; Deprecated=}.OperationId) | Get a PostgreSQL Managed Database |
| PUT | $(@{Tag=PostgreSQL; Method=PUT; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}; OperationId=put-databases-postgre-sql-instance; Summary=Update a PostgreSQL Managed Database; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=PUT; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}; OperationId=put-databases-postgre-sql-instance; Summary=Update a PostgreSQL Managed Database; Deprecated=}.OperationId) | Update a PostgreSQL Managed Database |
| GET | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools; OperationId=get-databases-postgre-sql-conn-pools; Summary=List PostgreSQL connection pools; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools; OperationId=get-databases-postgre-sql-conn-pools; Summary=List PostgreSQL connection pools; Deprecated=}.OperationId) | List PostgreSQL connection pools |
| POST | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools; OperationId=post-databases-postgre-sql-conn-pools; Summary=Create a PostgreSQL connection pool; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools; OperationId=post-databases-postgre-sql-conn-pools; Summary=Create a PostgreSQL connection pool; Deprecated=}.OperationId) | Create a PostgreSQL connection pool |
| DELETE | $(@{Tag=PostgreSQL; Method=DELETE; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools/{poolName}; OperationId=delete-databases-postgre-sql-conn-pool; Summary=Delete a PostgreSQL connection pool; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=DELETE; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools/{poolName}; OperationId=delete-databases-postgre-sql-conn-pool; Summary=Delete a PostgreSQL connection pool; Deprecated=}.OperationId) | Delete a PostgreSQL connection pool |
| GET | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools/{poolName}; OperationId=get-databases-postgre-sql-conn-pool; Summary=Get a PostgreSQL connection pool; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools/{poolName}; OperationId=get-databases-postgre-sql-conn-pool; Summary=Get a PostgreSQL connection pool; Deprecated=}.OperationId) | Get a PostgreSQL connection pool |
| PUT | $(@{Tag=PostgreSQL; Method=PUT; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools/{poolName}; OperationId=put-databases-postgre-sql-conn-pool; Summary=Update a PostgreSQL connection pool; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=PUT; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/connection-pools/{poolName}; OperationId=put-databases-postgre-sql-conn-pool; Summary=Update a PostgreSQL connection pool; Deprecated=}.OperationId) | Update a PostgreSQL connection pool |
| GET | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/credentials; OperationId=get-databases-postgre-sql-instance-credentials; Summary=Get PostgreSQL Managed Database credentials; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/credentials; OperationId=get-databases-postgre-sql-instance-credentials; Summary=Get PostgreSQL Managed Database credentials; Deprecated=}.OperationId) | Get PostgreSQL Managed Database credentials |
| POST | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/credentials/reset; OperationId=post-databases-postgre-sql-instance-credentials-reset; Summary=Reset PostgreSQL Managed Database credentials; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/credentials/reset; OperationId=post-databases-postgre-sql-instance-credentials-reset; Summary=Reset PostgreSQL Managed Database credentials; Deprecated=}.OperationId) | Reset PostgreSQL Managed Database credentials |
| POST | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/patch; OperationId=post-databases-postgre-sql-instance-patch; Summary=Patch a PostgreSQL Managed Database; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/patch; OperationId=post-databases-postgre-sql-instance-patch; Summary=Patch a PostgreSQL Managed Database; Deprecated=}.OperationId) | Patch a PostgreSQL Managed Database |
| POST | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/resume; OperationId=resume-databases-postgre-sql-instance; Summary=Resume a PostgreSQL Managed Database; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/resume; OperationId=resume-databases-postgre-sql-instance; Summary=Resume a PostgreSQL Managed Database; Deprecated=}.OperationId) | Resume a PostgreSQL Managed Database |
| GET | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/ssl; OperationId=get-databases-postgresql-instance-ssl; Summary=Get a PostgreSQL Managed Database SSL certificate; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=GET; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/ssl; OperationId=get-databases-postgresql-instance-ssl; Summary=Get a PostgreSQL Managed Database SSL certificate; Deprecated=}.OperationId) | Get a PostgreSQL Managed Database SSL certificate |
| POST | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/suspend; OperationId=suspend-databases-postgre-sql-instance; Summary=Suspend a PostgreSQL Managed Database; Deprecated=}.Path) | $(@{Tag=PostgreSQL; Method=POST; Path=/v4/databases/postgresql/instances/{postgresqlInstanceId}/suspend; OperationId=suspend-databases-postgre-sql-instance; Summary=Suspend a PostgreSQL Managed Database; Deprecated=}.OperationId) | Suspend a PostgreSQL Managed Database |

## Profile

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Profile; Method=GET; Path=/v4/profile; OperationId=get-profile; Summary=Get a profile; Deprecated=}.Path) | $(@{Tag=Profile; Method=GET; Path=/v4/profile; OperationId=get-profile; Summary=Get a profile; Deprecated=}.OperationId) | Get a profile |
| PUT | $(@{Tag=Profile; Method=PUT; Path=/v4/profile; OperationId=put-profile; Summary=Update a profile; Deprecated=}.Path) | $(@{Tag=Profile; Method=PUT; Path=/v4/profile; OperationId=put-profile; Summary=Update a profile; Deprecated=}.OperationId) | Update a profile |

## Profile logins

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Profile logins; Method=GET; Path=/v4/profile/logins; OperationId=get-profile-logins; Summary=List logins; Deprecated=}.Path) | $(@{Tag=Profile logins; Method=GET; Path=/v4/profile/logins; OperationId=get-profile-logins; Summary=List logins; Deprecated=}.OperationId) | List logins |
| GET | $(@{Tag=Profile logins; Method=GET; Path=/v4/profile/logins/{loginId}; OperationId=get-profile-login; Summary=Get a profile's login; Deprecated=}.Path) | $(@{Tag=Profile logins; Method=GET; Path=/v4/profile/logins/{loginId}; OperationId=get-profile-login; Summary=Get a profile's login; Deprecated=}.OperationId) | Get a profile's login |

## Promo credits

| Method | Path | operationId | Summary |
|---|---|---|---|
| POST | $(@{Tag=Promo credits; Method=POST; Path=/v4/account/promo-codes; OperationId=post-promo-credit; Summary=Add a promo credit; Deprecated=}.Path) | $(@{Tag=Promo credits; Method=POST; Path=/v4/account/promo-codes; OperationId=post-promo-credit; Summary=Add a promo credit; Deprecated=}.OperationId) | Add a promo credit |

## Quotas

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/global-quotas; OperationId=get-object-storage-global-quotas; Summary=List Object Storage global quotas; Deprecated=}.Path) | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/global-quotas; OperationId=get-object-storage-global-quotas; Summary=List Object Storage global quotas; Deprecated=}.OperationId) | List Object Storage global quotas |
| GET | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/global-quotas/{objGlobalQuotaId}; OperationId=get-object-storage-global-quota; Summary=Get an Object Storage global quota; Deprecated=}.Path) | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/global-quotas/{objGlobalQuotaId}; OperationId=get-object-storage-global-quota; Summary=Get an Object Storage global quota; Deprecated=}.OperationId) | Get an Object Storage global quota |
| GET | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/global-quotas/{objGlobalQuotaId}/usage; OperationId=get-object-storage-global-quota-usage; Summary=Get Object Storage global quota usage data; Deprecated=}.Path) | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/global-quotas/{objGlobalQuotaId}/usage; OperationId=get-object-storage-global-quota-usage; Summary=Get Object Storage global quota usage data; Deprecated=}.OperationId) | Get Object Storage global quota usage data |
| GET | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/quotas; OperationId=get-object-storage-quotas; Summary=List Object Storage quotas; Deprecated=}.Path) | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/quotas; OperationId=get-object-storage-quotas; Summary=List Object Storage quotas; Deprecated=}.OperationId) | List Object Storage quotas |
| GET | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/quotas/{objQuotaId}; OperationId=get-object-storage-quota; Summary=Get an Object Storage quota; Deprecated=}.Path) | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/quotas/{objQuotaId}; OperationId=get-object-storage-quota; Summary=Get an Object Storage quota; Deprecated=}.OperationId) | Get an Object Storage quota |
| GET | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/quotas/{objQuotaId}/usage; OperationId=get-object-storage-quota-usage; Summary=Get Object Storage quota usage data; Deprecated=}.Path) | $(@{Tag=Quotas; Method=GET; Path=/v4/object-storage/quotas/{objQuotaId}/usage; OperationId=get-object-storage-quota-usage; Summary=Get Object Storage quota usage data; Deprecated=}.OperationId) | Get Object Storage quota usage data |

## Regions

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Regions; Method=GET; Path=/v4/regions; OperationId=get-regions; Summary=List regions; Deprecated=}.Path) | $(@{Tag=Regions; Method=GET; Path=/v4/regions; OperationId=get-regions; Summary=List regions; Deprecated=}.OperationId) | List regions |
| GET | $(@{Tag=Regions; Method=GET; Path=/v4/regions/{regionId}; OperationId=get-region; Summary=Get a region; Deprecated=}.Path) | $(@{Tag=Regions; Method=GET; Path=/v4/regions/{regionId}; OperationId=get-region; Summary=Get a region; Deprecated=}.OperationId) | Get a region |
| GET | $(@{Tag=Regions; Method=GET; Path=/v4/regions/{regionId}/availability; OperationId=get-region-availability; Summary=Get a region's availability; Deprecated=}.Path) | $(@{Tag=Regions; Method=GET; Path=/v4/regions/{regionId}/availability; OperationId=get-region-availability; Summary=Get a region's availability; Deprecated=}.OperationId) | Get a region's availability |
| GET | $(@{Tag=Regions; Method=GET; Path=/v4/regions/availability; OperationId=get-regions-availability; Summary=List regions' availability; Deprecated=}.Path) | $(@{Tag=Regions; Method=GET; Path=/v4/regions/availability; OperationId=get-regions-availability; Summary=List regions' availability; Deprecated=}.OperationId) | List regions' availability |

## Replies

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Replies; Method=GET; Path=/v4/support/tickets/{ticketId}/replies; OperationId=get-ticket-replies; Summary=List replies; Deprecated=}.Path) | $(@{Tag=Replies; Method=GET; Path=/v4/support/tickets/{ticketId}/replies; OperationId=get-ticket-replies; Summary=List replies; Deprecated=}.OperationId) | List replies |
| POST | $(@{Tag=Replies; Method=POST; Path=/v4/support/tickets/{ticketId}/replies; OperationId=post-ticket-reply; Summary=Create a reply; Deprecated=}.Path) | $(@{Tag=Replies; Method=POST; Path=/v4/support/tickets/{ticketId}/replies; OperationId=post-ticket-reply; Summary=Create a reply; Deprecated=}.OperationId) | Create a reply |

## Resource locks

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Resource locks; Method=GET; Path=/v4/locks; OperationId=get-resource-locks; Summary=List resource locks; Deprecated=}.Path) | $(@{Tag=Resource locks; Method=GET; Path=/v4/locks; OperationId=get-resource-locks; Summary=List resource locks; Deprecated=}.OperationId) | List resource locks |
| POST | $(@{Tag=Resource locks; Method=POST; Path=/v4/locks; OperationId=post-resource-lock; Summary=Create a resource lock; Deprecated=}.Path) | $(@{Tag=Resource locks; Method=POST; Path=/v4/locks; OperationId=post-resource-lock; Summary=Create a resource lock; Deprecated=}.OperationId) | Create a resource lock |
| DELETE | $(@{Tag=Resource locks; Method=DELETE; Path=/v4/locks/{resourceLockId}; OperationId=delete-resource-lock; Summary=Delete a resource lock; Deprecated=}.Path) | $(@{Tag=Resource locks; Method=DELETE; Path=/v4/locks/{resourceLockId}; OperationId=delete-resource-lock; Summary=Delete a resource lock; Deprecated=}.OperationId) | Delete a resource lock |
| GET | $(@{Tag=Resource locks; Method=GET; Path=/v4/locks/{resourceLockId}; OperationId=get-resource-lock; Summary=Get a resource lock; Deprecated=}.Path) | $(@{Tag=Resource locks; Method=GET; Path=/v4/locks/{resourceLockId}; OperationId=get-resource-lock; Summary=Get a resource lock; Deprecated=}.OperationId) | Get a resource lock |

## Review Beta programs

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Review Beta programs; Method=GET; Path=/v4/betas; OperationId=get-beta-programs; Summary=List Beta programs; Deprecated=}.Path) | $(@{Tag=Review Beta programs; Method=GET; Path=/v4/betas; OperationId=get-beta-programs; Summary=List Beta programs; Deprecated=}.OperationId) | List Beta programs |
| GET | $(@{Tag=Review Beta programs; Method=GET; Path=/v4/betas/{betaId}; OperationId=get-beta-program; Summary=Get a Beta program; Deprecated=}.Path) | $(@{Tag=Review Beta programs; Method=GET; Path=/v4/betas/{betaId}; OperationId=get-beta-program; Summary=Get a Beta program; Deprecated=}.OperationId) | Get a Beta program |

## Security questions

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Security questions; Method=GET; Path=/v4/profile/security-questions; OperationId=get-security-questions; Summary=List security questions; Deprecated=}.Path) | $(@{Tag=Security questions; Method=GET; Path=/v4/profile/security-questions; OperationId=get-security-questions; Summary=List security questions; Deprecated=}.OperationId) | List security questions |
| POST | $(@{Tag=Security questions; Method=POST; Path=/v4/profile/security-questions; OperationId=post-security-questions; Summary=Answer security questions; Deprecated=}.Path) | $(@{Tag=Security questions; Method=POST; Path=/v4/profile/security-questions; OperationId=post-security-questions; Summary=Answer security questions; Deprecated=}.OperationId) | Answer security questions |

## Service transfers

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Service transfers; Method=GET; Path=/v4/account/service-transfers; OperationId=get-service-transfers; Summary=List service transfers; Deprecated=}.Path) | $(@{Tag=Service transfers; Method=GET; Path=/v4/account/service-transfers; OperationId=get-service-transfers; Summary=List service transfers; Deprecated=}.OperationId) | List service transfers |
| POST | $(@{Tag=Service transfers; Method=POST; Path=/v4/account/service-transfers; OperationId=post-service-transfer; Summary=Request a service transfer; Deprecated=}.Path) | $(@{Tag=Service transfers; Method=POST; Path=/v4/account/service-transfers; OperationId=post-service-transfer; Summary=Request a service transfer; Deprecated=}.OperationId) | Request a service transfer |
| DELETE | $(@{Tag=Service transfers; Method=DELETE; Path=/v4/account/service-transfers/{token}; OperationId=delete-service-transfer; Summary=Cancel a service transfer; Deprecated=}.Path) | $(@{Tag=Service transfers; Method=DELETE; Path=/v4/account/service-transfers/{token}; OperationId=delete-service-transfer; Summary=Cancel a service transfer; Deprecated=}.OperationId) | Cancel a service transfer |
| GET | $(@{Tag=Service transfers; Method=GET; Path=/v4/account/service-transfers/{token}; OperationId=get-service-transfer; Summary=Get a service transfer request; Deprecated=}.Path) | $(@{Tag=Service transfers; Method=GET; Path=/v4/account/service-transfers/{token}; OperationId=get-service-transfer; Summary=Get a service transfer request; Deprecated=}.OperationId) | Get a service transfer request |
| POST | $(@{Tag=Service transfers; Method=POST; Path=/v4/account/service-transfers/{token}/accept; OperationId=post-accept-service-transfer; Summary=Accept a service transfer; Deprecated=}.Path) | $(@{Tag=Service transfers; Method=POST; Path=/v4/account/service-transfers/{token}/accept; OperationId=post-accept-service-transfer; Summary=Accept a service transfer; Deprecated=}.OperationId) | Accept a service transfer |

## SSH keys

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=SSH keys; Method=GET; Path=/v4/profile/sshkeys; OperationId=get-ssh-keys; Summary=List SSH keys; Deprecated=}.Path) | $(@{Tag=SSH keys; Method=GET; Path=/v4/profile/sshkeys; OperationId=get-ssh-keys; Summary=List SSH keys; Deprecated=}.OperationId) | List SSH keys |
| POST | $(@{Tag=SSH keys; Method=POST; Path=/v4/profile/sshkeys; OperationId=post-add-ssh-key; Summary=Add an SSH key; Deprecated=}.Path) | $(@{Tag=SSH keys; Method=POST; Path=/v4/profile/sshkeys; OperationId=post-add-ssh-key; Summary=Add an SSH key; Deprecated=}.OperationId) | Add an SSH key |
| DELETE | $(@{Tag=SSH keys; Method=DELETE; Path=/v4/profile/sshkeys/{sshKeyId}; OperationId=delete-ssh-key; Summary=Delete an SSH key; Deprecated=}.Path) | $(@{Tag=SSH keys; Method=DELETE; Path=/v4/profile/sshkeys/{sshKeyId}; OperationId=delete-ssh-key; Summary=Delete an SSH key; Deprecated=}.OperationId) | Delete an SSH key |
| GET | $(@{Tag=SSH keys; Method=GET; Path=/v4/profile/sshkeys/{sshKeyId}; OperationId=get-ssh-key; Summary=Get an SSH key; Deprecated=}.Path) | $(@{Tag=SSH keys; Method=GET; Path=/v4/profile/sshkeys/{sshKeyId}; OperationId=get-ssh-key; Summary=Get an SSH key; Deprecated=}.OperationId) | Get an SSH key |
| PUT | $(@{Tag=SSH keys; Method=PUT; Path=/v4/profile/sshkeys/{sshKeyId}; OperationId=put-ssh-key; Summary=Update an SSH key; Deprecated=}.Path) | $(@{Tag=SSH keys; Method=PUT; Path=/v4/profile/sshkeys/{sshKeyId}; OperationId=put-ssh-key; Summary=Update an SSH key; Deprecated=}.OperationId) | Update an SSH key |

## StackScripts

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=StackScripts; Method=GET; Path=/v4/linode/stackscripts; OperationId=get-stack-scripts; Summary=List StackScripts; Deprecated=}.Path) | $(@{Tag=StackScripts; Method=GET; Path=/v4/linode/stackscripts; OperationId=get-stack-scripts; Summary=List StackScripts; Deprecated=}.OperationId) | List StackScripts |
| POST | $(@{Tag=StackScripts; Method=POST; Path=/v4/linode/stackscripts; OperationId=post-add-stack-script; Summary=Create a StackScript; Deprecated=}.Path) | $(@{Tag=StackScripts; Method=POST; Path=/v4/linode/stackscripts; OperationId=post-add-stack-script; Summary=Create a StackScript; Deprecated=}.OperationId) | Create a StackScript |
| DELETE | $(@{Tag=StackScripts; Method=DELETE; Path=/v4/linode/stackscripts/{stackscriptId}; OperationId=delete-stack-script; Summary=Delete a StackScript; Deprecated=}.Path) | $(@{Tag=StackScripts; Method=DELETE; Path=/v4/linode/stackscripts/{stackscriptId}; OperationId=delete-stack-script; Summary=Delete a StackScript; Deprecated=}.OperationId) | Delete a StackScript |
| GET | $(@{Tag=StackScripts; Method=GET; Path=/v4/linode/stackscripts/{stackscriptId}; OperationId=get-stack-script; Summary=Get a StackScript; Deprecated=}.Path) | $(@{Tag=StackScripts; Method=GET; Path=/v4/linode/stackscripts/{stackscriptId}; OperationId=get-stack-script; Summary=Get a StackScript; Deprecated=}.OperationId) | Get a StackScript |
| PUT | $(@{Tag=StackScripts; Method=PUT; Path=/v4/linode/stackscripts/{stackscriptId}; OperationId=put-stack-script; Summary=Update a StackScript; Deprecated=}.Path) | $(@{Tag=StackScripts; Method=PUT; Path=/v4/linode/stackscripts/{stackscriptId}; OperationId=put-stack-script; Summary=Update a StackScript; Deprecated=}.OperationId) | Update a StackScript |

## Support tickets

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Support tickets; Method=GET; Path=/v4/support/tickets; OperationId=get-tickets; Summary=List support tickets; Deprecated=}.Path) | $(@{Tag=Support tickets; Method=GET; Path=/v4/support/tickets; OperationId=get-tickets; Summary=List support tickets; Deprecated=}.OperationId) | List support tickets |
| POST | $(@{Tag=Support tickets; Method=POST; Path=/v4/support/tickets; OperationId=post-ticket; Summary=Open a support ticket; Deprecated=}.Path) | $(@{Tag=Support tickets; Method=POST; Path=/v4/support/tickets; OperationId=post-ticket; Summary=Open a support ticket; Deprecated=}.OperationId) | Open a support ticket |
| GET | $(@{Tag=Support tickets; Method=GET; Path=/v4/support/tickets/{ticketId}; OperationId=get-ticket; Summary=Get a support ticket; Deprecated=}.Path) | $(@{Tag=Support tickets; Method=GET; Path=/v4/support/tickets/{ticketId}; OperationId=get-ticket; Summary=Get a support ticket; Deprecated=}.OperationId) | Get a support ticket |
| POST | $(@{Tag=Support tickets; Method=POST; Path=/v4/support/tickets/{ticketId}/close; OperationId=post-close-ticket; Summary=Close a support ticket; Deprecated=}.Path) | $(@{Tag=Support tickets; Method=POST; Path=/v4/support/tickets/{ticketId}/close; OperationId=post-close-ticket; Summary=Close a support ticket; Deprecated=}.OperationId) | Close a support ticket |

## Tags

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Tags; Method=GET; Path=/v4/tags; OperationId=get-tags; Summary=List tags; Deprecated=}.Path) | $(@{Tag=Tags; Method=GET; Path=/v4/tags; OperationId=get-tags; Summary=List tags; Deprecated=}.OperationId) | List tags |
| POST | $(@{Tag=Tags; Method=POST; Path=/v4/tags; OperationId=post-tag; Summary=Create a tag; Deprecated=}.Path) | $(@{Tag=Tags; Method=POST; Path=/v4/tags; OperationId=post-tag; Summary=Create a tag; Deprecated=}.OperationId) | Create a tag |
| DELETE | $(@{Tag=Tags; Method=DELETE; Path=/v4/tags/{tagLabel}; OperationId=delete-tag; Summary=Delete a tag; Deprecated=}.Path) | $(@{Tag=Tags; Method=DELETE; Path=/v4/tags/{tagLabel}; OperationId=delete-tag; Summary=Delete a tag; Deprecated=}.OperationId) | Delete a tag |
| GET | $(@{Tag=Tags; Method=GET; Path=/v4/tags/{tagLabel}; OperationId=get-tagged-objects; Summary=List tagged objects; Deprecated=}.Path) | $(@{Tag=Tags; Method=GET; Path=/v4/tags/{tagLabel}; OperationId=get-tagged-objects; Summary=List tagged objects; Deprecated=}.OperationId) | List tagged objects |

## Templates

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Templates; Method=GET; Path=/v4/networking/firewalls/templates; OperationId=get-firewall-templates; Summary=List firewall templates; Deprecated=}.Path) | $(@{Tag=Templates; Method=GET; Path=/v4/networking/firewalls/templates; OperationId=get-firewall-templates; Summary=List firewall templates; Deprecated=}.OperationId) | List firewall templates |
| GET | $(@{Tag=Templates; Method=GET; Path=/v4/networking/firewalls/templates/{slug}; OperationId=get-firewall-template; Summary=Get a firewall template; Deprecated=}.Path) | $(@{Tag=Templates; Method=GET; Path=/v4/networking/firewalls/templates/{slug}; OperationId=get-firewall-template; Summary=Get a firewall template; Deprecated=}.OperationId) | Get a firewall template |

## TLS/SSL certificates

| Method | Path | operationId | Summary |
|---|---|---|---|
| DELETE | $(@{Tag=TLS/SSL certificates; Method=DELETE; Path=/v4/object-storage/buckets/{regionId}/{bucket}/ssl; OperationId=delete-object-storage-ssl; Summary=Delete an Object Storage TLS/SSL certificate; Deprecated=}.Path) | $(@{Tag=TLS/SSL certificates; Method=DELETE; Path=/v4/object-storage/buckets/{regionId}/{bucket}/ssl; OperationId=delete-object-storage-ssl; Summary=Delete an Object Storage TLS/SSL certificate; Deprecated=}.OperationId) | Delete an Object Storage TLS/SSL certificate |
| GET | $(@{Tag=TLS/SSL certificates; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/ssl; OperationId=get-object-storage-ssl; Summary=Get an Object Storage TLS/SSL certificate; Deprecated=}.Path) | $(@{Tag=TLS/SSL certificates; Method=GET; Path=/v4/object-storage/buckets/{regionId}/{bucket}/ssl; OperationId=get-object-storage-ssl; Summary=Get an Object Storage TLS/SSL certificate; Deprecated=}.OperationId) | Get an Object Storage TLS/SSL certificate |
| POST | $(@{Tag=TLS/SSL certificates; Method=POST; Path=/v4/object-storage/buckets/{regionId}/{bucket}/ssl; OperationId=post-object-storage-ssl; Summary=Upload an Object Storage TLS/SSL certificate; Deprecated=}.Path) | $(@{Tag=TLS/SSL certificates; Method=POST; Path=/v4/object-storage/buckets/{regionId}/{bucket}/ssl; OperationId=post-object-storage-ssl; Summary=Upload an Object Storage TLS/SSL certificate; Deprecated=}.OperationId) | Upload an Object Storage TLS/SSL certificate |

## Trusted devices

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Trusted devices; Method=GET; Path=/v4/profile/devices; OperationId=get-devices; Summary=List trusted devices; Deprecated=}.Path) | $(@{Tag=Trusted devices; Method=GET; Path=/v4/profile/devices; OperationId=get-devices; Summary=List trusted devices; Deprecated=}.OperationId) | List trusted devices |
| DELETE | $(@{Tag=Trusted devices; Method=DELETE; Path=/v4/profile/devices/{deviceId}; OperationId=delete-trusted-device; Summary=Revoke a trusted device; Deprecated=}.Path) | $(@{Tag=Trusted devices; Method=DELETE; Path=/v4/profile/devices/{deviceId}; OperationId=delete-trusted-device; Summary=Revoke a trusted device; Deprecated=}.OperationId) | Revoke a trusted device |
| GET | $(@{Tag=Trusted devices; Method=GET; Path=/v4/profile/devices/{deviceId}; OperationId=get-trusted-device; Summary=Get a trusted device; Deprecated=}.Path) | $(@{Tag=Trusted devices; Method=GET; Path=/v4/profile/devices/{deviceId}; OperationId=get-trusted-device; Summary=Get a trusted device; Deprecated=}.OperationId) | Get a trusted device |

## Two-factor authentication

| Method | Path | operationId | Summary |
|---|---|---|---|
| POST | $(@{Tag=Two-factor authentication; Method=POST; Path=/v4/profile/tfa-disable; OperationId=post-tfa-disable; Summary=Disable two-factor authentication; Deprecated=}.Path) | $(@{Tag=Two-factor authentication; Method=POST; Path=/v4/profile/tfa-disable; OperationId=post-tfa-disable; Summary=Disable two-factor authentication; Deprecated=}.OperationId) | Disable two-factor authentication |
| POST | $(@{Tag=Two-factor authentication; Method=POST; Path=/v4/profile/tfa-enable; OperationId=post-tfa-enable; Summary=Generate a secret key for two-factor authentication; Deprecated=}.Path) | $(@{Tag=Two-factor authentication; Method=POST; Path=/v4/profile/tfa-enable; OperationId=post-tfa-enable; Summary=Generate a secret key for two-factor authentication; Deprecated=}.OperationId) | Generate a secret key for two-factor authentication |
| POST | $(@{Tag=Two-factor authentication; Method=POST; Path=/v4/profile/tfa-enable-confirm; OperationId=post-tfa-confirm; Summary=Enable two-factor authentication; Deprecated=}.Path) | $(@{Tag=Two-factor authentication; Method=POST; Path=/v4/profile/tfa-enable-confirm; OperationId=post-tfa-confirm; Summary=Enable two-factor authentication; Deprecated=}.OperationId) | Enable two-factor authentication |

## Users

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Users; Method=GET; Path=/v4/account/users; OperationId=get-users; Summary=List users; Deprecated=}.Path) | $(@{Tag=Users; Method=GET; Path=/v4/account/users; OperationId=get-users; Summary=List users; Deprecated=}.OperationId) | List users |
| POST | $(@{Tag=Users; Method=POST; Path=/v4/account/users; OperationId=post-user; Summary=Create a user; Deprecated=}.Path) | $(@{Tag=Users; Method=POST; Path=/v4/account/users; OperationId=post-user; Summary=Create a user; Deprecated=}.OperationId) | Create a user |
| DELETE | $(@{Tag=Users; Method=DELETE; Path=/v4/account/users/{username}; OperationId=delete-user; Summary=Delete a user; Deprecated=}.Path) | $(@{Tag=Users; Method=DELETE; Path=/v4/account/users/{username}; OperationId=delete-user; Summary=Delete a user; Deprecated=}.OperationId) | Delete a user |
| GET | $(@{Tag=Users; Method=GET; Path=/v4/account/users/{username}; OperationId=get-user; Summary=Get a user; Deprecated=}.Path) | $(@{Tag=Users; Method=GET; Path=/v4/account/users/{username}; OperationId=get-user; Summary=Get a user; Deprecated=}.OperationId) | Get a user |
| PUT | $(@{Tag=Users; Method=PUT; Path=/v4/account/users/{username}; OperationId=put-user; Summary=Update a user; Deprecated=}.Path) | $(@{Tag=Users; Method=PUT; Path=/v4/account/users/{username}; OperationId=put-user; Summary=Update a user; Deprecated=}.OperationId) | Update a user |
| GET | $(@{Tag=Users; Method=GET; Path=/v4/account/users/{username}/grants; OperationId=get-user-grants; Summary=List a user's grants; Deprecated=yes}.Path) | $(@{Tag=Users; Method=GET; Path=/v4/account/users/{username}/grants; OperationId=get-user-grants; Summary=List a user's grants; Deprecated=yes}.OperationId) | [DEPRECATED] List a user's grants |
| PUT | $(@{Tag=Users; Method=PUT; Path=/v4/account/users/{username}/grants; OperationId=put-user-grants; Summary=Update a user's grants; Deprecated=yes}.Path) | $(@{Tag=Users; Method=PUT; Path=/v4/account/users/{username}/grants; OperationId=put-user-grants; Summary=Update a user's grants; Deprecated=yes}.OperationId) | [DEPRECATED] Update a user's grants |

## VLANs

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=VLANs; Method=GET; Path=/v4/networking/vlans; OperationId=get-vlans; Summary=List VLANs; Deprecated=}.Path) | $(@{Tag=VLANs; Method=GET; Path=/v4/networking/vlans; OperationId=get-vlans; Summary=List VLANs; Deprecated=}.OperationId) | List VLANs |
| DELETE | $(@{Tag=VLANs; Method=DELETE; Path=/v4/networking/vlans/{regionId}/{label}; OperationId=delete-vlan; Summary=Delete a VLAN; Deprecated=}.Path) | $(@{Tag=VLANs; Method=DELETE; Path=/v4/networking/vlans/{regionId}/{label}; OperationId=delete-vlan; Summary=Delete a VLAN; Deprecated=}.OperationId) | Delete a VLAN |

## Volume types

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Volume types; Method=GET; Path=/v4/volumes/types; OperationId=get-volume-types; Summary=List volume types; Deprecated=}.Path) | $(@{Tag=Volume types; Method=GET; Path=/v4/volumes/types; OperationId=get-volume-types; Summary=List volume types; Deprecated=}.OperationId) | List volume types |

## Volumes

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=Volumes; Method=GET; Path=/v4/volumes; OperationId=get-volumes; Summary=List volumes; Deprecated=}.Path) | $(@{Tag=Volumes; Method=GET; Path=/v4/volumes; OperationId=get-volumes; Summary=List volumes; Deprecated=}.OperationId) | List volumes |
| POST | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes; OperationId=post-volume; Summary=Create a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes; OperationId=post-volume; Summary=Create a volume; Deprecated=}.OperationId) | Create a volume |
| DELETE | $(@{Tag=Volumes; Method=DELETE; Path=/v4/volumes/{volumeId}; OperationId=delete-volume; Summary=Delete a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=DELETE; Path=/v4/volumes/{volumeId}; OperationId=delete-volume; Summary=Delete a volume; Deprecated=}.OperationId) | Delete a volume |
| GET | $(@{Tag=Volumes; Method=GET; Path=/v4/volumes/{volumeId}; OperationId=get-volume; Summary=Get a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=GET; Path=/v4/volumes/{volumeId}; OperationId=get-volume; Summary=Get a volume; Deprecated=}.OperationId) | Get a volume |
| PUT | $(@{Tag=Volumes; Method=PUT; Path=/v4/volumes/{volumeId}; OperationId=put-volume; Summary=Update a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=PUT; Path=/v4/volumes/{volumeId}; OperationId=put-volume; Summary=Update a volume; Deprecated=}.OperationId) | Update a volume |
| POST | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/attach; OperationId=post-attach-volume; Summary=Attach a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/attach; OperationId=post-attach-volume; Summary=Attach a volume; Deprecated=}.OperationId) | Attach a volume |
| POST | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/clone; OperationId=post-clone-volume; Summary=Clone a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/clone; OperationId=post-clone-volume; Summary=Clone a volume; Deprecated=}.OperationId) | Clone a volume |
| POST | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/detach; OperationId=post-detach-volume; Summary=Detach a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/detach; OperationId=post-detach-volume; Summary=Detach a volume; Deprecated=}.OperationId) | Detach a volume |
| POST | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/resize; OperationId=post-resize-volume; Summary=Resize a volume; Deprecated=}.Path) | $(@{Tag=Volumes; Method=POST; Path=/v4/volumes/{volumeId}/resize; OperationId=post-resize-volume; Summary=Resize a volume; Deprecated=}.OperationId) | Resize a volume |

## VPC IP addresses

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=VPC IP addresses; Method=GET; Path=/v4/vpcs/{vpcId}/ips; OperationId=get-vpc-ips; Summary=List a VPC's IP addresses; Deprecated=}.Path) | $(@{Tag=VPC IP addresses; Method=GET; Path=/v4/vpcs/{vpcId}/ips; OperationId=get-vpc-ips; Summary=List a VPC's IP addresses; Deprecated=}.OperationId) | List a VPC's IP addresses |
| GET | $(@{Tag=VPC IP addresses; Method=GET; Path=/v4/vpcs/ips; OperationId=get-vpcs-ips; Summary=List VPC IP addresses; Deprecated=}.Path) | $(@{Tag=VPC IP addresses; Method=GET; Path=/v4/vpcs/ips; OperationId=get-vpcs-ips; Summary=List VPC IP addresses; Deprecated=}.OperationId) | List VPC IP addresses |

## VPC subnets

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=VPC subnets; Method=GET; Path=/v4/vpcs/{vpcId}/subnets; OperationId=get-vpc-subnets; Summary=List VPC subnets; Deprecated=}.Path) | $(@{Tag=VPC subnets; Method=GET; Path=/v4/vpcs/{vpcId}/subnets; OperationId=get-vpc-subnets; Summary=List VPC subnets; Deprecated=}.OperationId) | List VPC subnets |
| POST | $(@{Tag=VPC subnets; Method=POST; Path=/v4/vpcs/{vpcId}/subnets; OperationId=post-vpc-subnet; Summary=Create a VPC subnet; Deprecated=}.Path) | $(@{Tag=VPC subnets; Method=POST; Path=/v4/vpcs/{vpcId}/subnets; OperationId=post-vpc-subnet; Summary=Create a VPC subnet; Deprecated=}.OperationId) | Create a VPC subnet |
| DELETE | $(@{Tag=VPC subnets; Method=DELETE; Path=/v4/vpcs/{vpcId}/subnets/{vpcSubnetId}; OperationId=delete-vpc-subnet; Summary=Delete a VPC subnet; Deprecated=}.Path) | $(@{Tag=VPC subnets; Method=DELETE; Path=/v4/vpcs/{vpcId}/subnets/{vpcSubnetId}; OperationId=delete-vpc-subnet; Summary=Delete a VPC subnet; Deprecated=}.OperationId) | Delete a VPC subnet |
| GET | $(@{Tag=VPC subnets; Method=GET; Path=/v4/vpcs/{vpcId}/subnets/{vpcSubnetId}; OperationId=get-vpc-subnet; Summary=Get a VPC subnet; Deprecated=}.Path) | $(@{Tag=VPC subnets; Method=GET; Path=/v4/vpcs/{vpcId}/subnets/{vpcSubnetId}; OperationId=get-vpc-subnet; Summary=Get a VPC subnet; Deprecated=}.OperationId) | Get a VPC subnet |
| PUT | $(@{Tag=VPC subnets; Method=PUT; Path=/v4/vpcs/{vpcId}/subnets/{vpcSubnetId}; OperationId=put-vpc-subnet; Summary=Update a VPC subnet; Deprecated=}.Path) | $(@{Tag=VPC subnets; Method=PUT; Path=/v4/vpcs/{vpcId}/subnets/{vpcSubnetId}; OperationId=put-vpc-subnet; Summary=Update a VPC subnet; Deprecated=}.OperationId) | Update a VPC subnet |

## VPCs

| Method | Path | operationId | Summary |
|---|---|---|---|
| GET | $(@{Tag=VPCs; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/vpcs; OperationId=get-node-balancer-vpcs; Summary=List VPC configurations; Deprecated=}.Path) | $(@{Tag=VPCs; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/vpcs; OperationId=get-node-balancer-vpcs; Summary=List VPC configurations; Deprecated=}.OperationId) | List VPC configurations |
| GET | $(@{Tag=VPCs; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/vpcs/{nodeBalancerVpcConfigId}; OperationId=get-node-balancer-vpc-config; Summary=Get a VPC configuration; Deprecated=}.Path) | $(@{Tag=VPCs; Method=GET; Path=/v4/nodebalancers/{nodeBalancerId}/vpcs/{nodeBalancerVpcConfigId}; OperationId=get-node-balancer-vpc-config; Summary=Get a VPC configuration; Deprecated=}.OperationId) | Get a VPC configuration |
| GET | $(@{Tag=VPCs; Method=GET; Path=/v4/vpcs; OperationId=get-vpcs; Summary=List VPCs; Deprecated=}.Path) | $(@{Tag=VPCs; Method=GET; Path=/v4/vpcs; OperationId=get-vpcs; Summary=List VPCs; Deprecated=}.OperationId) | List VPCs |
| POST | $(@{Tag=VPCs; Method=POST; Path=/v4/vpcs; OperationId=post-vpc; Summary=Create a VPC; Deprecated=}.Path) | $(@{Tag=VPCs; Method=POST; Path=/v4/vpcs; OperationId=post-vpc; Summary=Create a VPC; Deprecated=}.OperationId) | Create a VPC |
| DELETE | $(@{Tag=VPCs; Method=DELETE; Path=/v4/vpcs/{vpcId}; OperationId=delete-vpc; Summary=Delete a VPC; Deprecated=}.Path) | $(@{Tag=VPCs; Method=DELETE; Path=/v4/vpcs/{vpcId}; OperationId=delete-vpc; Summary=Delete a VPC; Deprecated=}.OperationId) | Delete a VPC |
| GET | $(@{Tag=VPCs; Method=GET; Path=/v4/vpcs/{vpcId}; OperationId=get-vpc; Summary=Get a VPC; Deprecated=}.Path) | $(@{Tag=VPCs; Method=GET; Path=/v4/vpcs/{vpcId}; OperationId=get-vpc; Summary=Get a VPC; Deprecated=}.OperationId) | Get a VPC |
| PUT | $(@{Tag=VPCs; Method=PUT; Path=/v4/vpcs/{vpcId}; OperationId=put-vpc; Summary=Update a VPC; Deprecated=}.Path) | $(@{Tag=VPCs; Method=PUT; Path=/v4/vpcs/{vpcId}; OperationId=put-vpc; Summary=Update a VPC; Deprecated=}.OperationId) | Update a VPC |

