# Use the official .NET image as a base image
FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS base
WORKDIR /app
EXPOSE 80

# Use the official .NET SDK image for building the application
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src
COPY ["JrvNetMigration.csproj", "./"]
RUN dotnet restore "./JrvNetMigration.csproj"
COPY . .
WORKDIR "/src/"
RUN dotnet build "JrvNetMigration.csproj" -c Release -o /app/build

# Publish the application
FROM build AS publish
RUN dotnet publish "JrvNetMigration.csproj" -c Release -o /app/publish

# Use the base image to run the application
FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "JrvNetMigration.dll"]