using NUnit.Framework;
using OpenQA.Selenium;
using OpenQA.Selenium.Chrome;
using OpenQA.Selenium.Support.UI;
using System;
using System.IO;
using System.Threading.Tasks;

namespace JrvNetMigration.Tests
{
    [TestFixture]
    public class AdminFileUploadTests : IDisposable
    {
        private IWebDriver _driver;
        private WebDriverWait _wait;
        private string _testZipFilePath;

        [OneTimeSetUp]
        public void Setup()
        {
            // Initialize Chrome driver
            _driver = new ChromeDriver();
            _wait = new WebDriverWait(_driver, TimeSpan.FromSeconds(10));

            // Create a test ZIP file
            var testFilesDir = Path.Combine(Path.GetTempPath(), "JrvNetTestFiles");
            Directory.CreateDirectory(testFilesDir);
            _testZipFilePath = Path.Combine(testFilesDir, "test_upload.zip");
            File.WriteAllBytes(_testZipFilePath, new byte[] { 80, 75, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 }); // Empty ZIP file
        }

        [OneTimeTearDown]
        public void TearDown()
        {
            _driver?.Quit();
            if (File.Exists(_testZipFilePath))
            {
                File.Delete(_testZipFilePath);
            }
        }

        [Test]
        public async Task AdminCanUploadFile()
        {
            try
            {
                // Navigate to login page
                _driver.Navigate().GoToUrl("http://localhost:5218/Admin/Login");

                // Login as admin
                var usernameInput = _driver.FindElement(By.Id("Username"));
                var passwordInput = _driver.FindElement(By.Id("Password"));
                var loginButton = _driver.FindElement(By.CssSelector("button[type='submit']"));

                usernameInput.SendKeys("admin"); // Replace with actual admin username
                passwordInput.SendKeys("admin123"); // Replace with actual admin password
                loginButton.Click();

                // Wait for and click the Upload Data link
                var uploadLink = _wait.Until(d => d.FindElement(By.LinkText("Upload Data")));
                uploadLink.Click();

                // Upload the test file
                var fileInput = _wait.Until(d => d.FindElement(By.Id("file")));
                fileInput.SendKeys(_testZipFilePath);

                // Submit the form
                var uploadButton = _driver.FindElement(By.CssSelector("button[type='submit']"));
                uploadButton.Click();

                // Verify success message
                var successMessage = _wait.Until(d => d.FindElement(By.CssSelector(".alert-success")));
                Assert.That(successMessage.Text, Contains.Substring("File uploaded successfully"));
            }
            catch (Exception ex)
            {
                Assert.Fail($"Test failed with error: {ex.Message}");
            }
        }

        public void Dispose()
        {
            _driver?.Dispose();
        }
    }
}