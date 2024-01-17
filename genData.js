const fs = require("fs");

const generateRandomData = () => {
  const data = [];

  for (let i = 0; i < 1000; i++) {
    const user = `user${i}`;
    const email = `email${i}@example.com`;
    const id = i;
    const time = Date.now() + i * (Math.floor(Math.random() * 100) + 1) * 1000; // Use the current time plus an increment for variety
    const status = Math.random() < 0.5; // Randomly set true or false

    data.push({
      user,
      email,
      id,
      time,
      status,
    });
  }

  return data;
};

const generatedData = generateRandomData();
const jsonData = JSON.stringify({ data: generatedData }, null, 2);

fs.writeFile("dummy.json", jsonData, (err) => {
  if (err) {
    console.error("Error writing to dummy.json:", err);
  } else {
    console.log("dummy.json has been created successfully.");
  }
});
