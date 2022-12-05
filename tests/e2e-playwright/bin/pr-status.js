#!/usr/bin/env node
// Octokit.js
// https://github.com/octokit/core.js#readme

const { Octokit } = require("@octokit/core");

const octokit = new Octokit({
  auth: process.env.TOKEN,
});

octokit.request("POST /repos/{org}/{repo}/statuses/{sha}", {
  org: "pavanpatil1",
  repo: "trust-txt",
  sha: process.env.SHA ? process.env.SHA : process.env.COMMIT_SHA,
  state: "success",
  conclusion: "success",
  target_url:
    "https://www.tesults.com/results/rsp/view/results/project/eb4798b1-30a0-4a2f-b8fd-ac01c998b154",
  description: "Successfully synced to Tesults",
  context: "E2E Test Result",
});