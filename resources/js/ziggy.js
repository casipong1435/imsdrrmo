const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"team.dashboard":{"uri":"team\/dashboard","methods":["GET","HEAD"]},"team.createIncidentReport":{"uri":"team\/create-incident-report","methods":["POST"]},"team.updateIncidentReport":{"uri":"team\/update-incident-report\/{id}","methods":["PUT"],"parameters":["id"]},"team.editIncidentDesctiption":{"uri":"team\/update-incident-description","methods":["PUT"]},"team.DownloadIncidentReport":{"uri":"team\/incident-report","methods":["GET","HEAD"]},"admin.dashboard":{"uri":"admin\/dashboard","methods":["GET","HEAD"]},"admin.account":{"uri":"admin\/account","methods":["GET","HEAD"]},"admin.certificate":{"uri":"admin\/certificate","methods":["GET","HEAD"]},"admin.log":{"uri":"admin\/log","methods":["GET","HEAD"]},"admin.incident":{"uri":"admin\/record\/incident","methods":["GET","HEAD"]},"admin.supply":{"uri":"admin\/record\/supply","methods":["GET","HEAD"]},"admin.alert":{"uri":"admin\/record\/calamity-alert","methods":["GET","HEAD"]},"admin.addAccount":{"uri":"admin\/add-acount","methods":["POST"]},"admin.addItem":{"uri":"admin\/add-item","methods":["POST"]},"admin.sendSMS":{"uri":"admin\/send-sms","methods":["POST"]},"admin.editAccount":{"uri":"admin\/edit-acount\/{id}","methods":["PUT"],"parameters":["id"]},"admin.editItem":{"uri":"admin\/edit-item\/{id}","methods":["PUT"],"parameters":["id"]},"admin.editItemQuantity":{"uri":"admin\/edit-item-quantity\/{id}","methods":["PUT"],"parameters":["id"]},"admin.changeAccountPassword":{"uri":"admin\/change-account-password\/{id}","methods":["PUT"],"parameters":["id"]},"admin.editIncidentDesctiption":{"uri":"admin\/update-incident-description","methods":["PUT"]},"admin.returnItem":{"uri":"admin\/return-item","methods":["PUT"]},"admin.deleteAccount":{"uri":"admin\/delete-acount\/{id}","methods":["DELETE"],"parameters":["id"]},"admin.deleteItem":{"uri":"admin\/delete-item\/{id}","methods":["DELETE"],"parameters":["id"]},"admin.deleteAllLog":{"uri":"admin\/delete-all-log","methods":["DELETE"]},"admin.filterSupplyActivity":{"uri":"admin\/filter-supply-activity","methods":["GET","HEAD"]},"admin.DownloadCertificate":{"uri":"admin\/certificate-of-damage","methods":["GET","HEAD"]},"admin.DownloadIncidentReport":{"uri":"admin\/incident-report","methods":["GET","HEAD"]},"admin.DownloadSupplyActivity":{"uri":"admin\/supply-activity","methods":["GET","HEAD"]},"user.dashboard":{"uri":"user\/dashboard","methods":["GET","HEAD"]},"user.requestCertificate":{"uri":"user\/submit-request","methods":["POST"]},"register":{"uri":"register","methods":["GET","HEAD"]},"login":{"uri":"login","methods":["GET","HEAD"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.verifyOTP":{"uri":"guest\/verify-otp","methods":["POST"]},"password.sendOTP":{"uri":"guest\/send-otp","methods":["POST"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"],"parameters":["token"]},"password.store":{"uri":"reset-password","methods":["POST"]},"verification.notice":{"uri":"verify-email","methods":["GET","HEAD"]},"verification.verify":{"uri":"verify-email\/{id}\/{hash}","methods":["GET","HEAD"],"parameters":["id","hash"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"]},"password.confirm":{"uri":"confirm-password","methods":["GET","HEAD"]},"password.update":{"uri":"password","methods":["PUT"]},"logout":{"uri":"logout","methods":["POST"]},"markasReadNotification":{"uri":"mark-as-read\/{id}","methods":["POST"],"parameters":["id"]},"markasReadAllNotification":{"uri":"mark-all-as-read","methods":["POST"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
