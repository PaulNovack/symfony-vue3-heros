import { exec } from 'child_process';
import inquirer from 'inquirer';
import { readFile } from 'fs/promises';

// Load the package.json file dynamically
const packageJson = JSON.parse(await readFile(new URL('./package.json', import.meta.url)));

// Extract the scripts from package.json
const scripts = packageJson.scripts;
const scriptKeys = Object.keys(scripts);

if (!scriptKeys.length) {
    console.log("No scripts available in package.json");
    process.exit(1);
}

// Use Inquirer to prompt the user to select a script
inquirer
    .prompt([
        {
            type: 'list',
            name: 'script',
            message: 'Which script would you like to run?',
            choices: scriptKeys
        }
    ])
    .then((answers) => {
        const scriptToRun = answers.script;

        // Execute the selected script
        console.log(`Running script: ${scriptToRun}`);
        exec(`npm run ${scriptToRun}`, (error, stdout, stderr) => {
            // Output stdout (successful output)
            if (stdout) {
                console.log(`Standard Output:\n${stdout}`);
            }

            // Output stderr (errors or warnings)
            if (stderr) {
                console.error(`Error Output:\n${stderr}`);
            }

            // Output error (if any)
            if (error) {
                console.error(`Execution Error: ${error.message}`);
            }
        });
    })
    .catch((error) => {
        console.error("Error in script selection:", error);
    });
