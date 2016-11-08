﻿using Protobuild.Website.Models;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Protobuild.Website.Services
{
    public interface IRepository
    {
        Task<UserModel> LoadUserByName(string name);

        Task<IEnumerable<PackageModel>> LoadAllPackagesForUser(UserModel user);

        Task<UserAndPackageResult> LoadUserAndPackageByNames(string user, string package);

        Task<List<BranchModel>> LoadAllBranchesForPackage(UserModel user, PackageModel package, int? limit = null);

        Task<List<VersionModel>> LoadAllVersionsForPackage(UserModel user, PackageModel package, int? limit = null);
    }

    public class UserAndPackageResult
    {
        public UserModel User { get; set; }

        public PackageModel Package { get; set; }
    }
}