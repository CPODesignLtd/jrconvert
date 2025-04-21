# Use the official .NET image as a base image
FROM mcr.microsoft.com/dotnet/aspnet:9.0 AS base
RUN apt-get update && apt-get install -y wget && rm -rf /var/lib/apt/lists/*
WORKDIR /app
EXPOSE 80
EXPOSE 443

# Use the official .NET SDK image for building the application
FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build
WORKDIR /src
COPY ["JrvNetMigration.csproj", "./"]
RUN dotnet restore "JrvNetMigration.csproj"
COPY . .
RUN dotnet build "JrvNetMigration.csproj" -c Release -o /app/build

# Publish the application
FROM build AS publish
RUN dotnet publish "JrvNetMigration.csproj" -c Release -o /app/publish

# Use the base image to run the application
FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "JrvNetMigration.dll"]