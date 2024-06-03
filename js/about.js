



// Convert this to:
document.addEventListener("DOMContentLoaded", function() {
  const teamContainer = document.getElementById("teamContainer");
  teamMembers.forEach(member => {
    const memberDiv = document.createElement("div");
    memberDiv.innerHTML = `
      <img src="${member.image}" alt="${member.name}" />
      <h2>${member.name}</h2>
      <p>${member.specialization}</p>
    `;
    teamContainer.appendChild(memberDiv);
  });
});
