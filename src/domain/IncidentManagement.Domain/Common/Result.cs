using System.Diagnostics.CodeAnalysis;

namespace IncidentManagement.Domain.Common;

public readonly record struct Result
{
    public bool IsSuccess { get; }

    public bool IsFailure => !IsSuccess;
    
    public Error? Error { get; }
    
    private Result(bool isSuccess, Error? error = null)
    {
        IsSuccess = isSuccess;
        Error = error;
    }
    
    public static Result Success() => new(true);
    
    public static Result Failure(Error error) => new(false, error);
};

public readonly record struct Result<T>
{
    public T? Value { get; }
    
    public bool IsSuccess { get; }

    public Error? Error { get; }
    
    private Result(T? value, bool isSuccess, Error? error = null)
    {
        Value = value;
        IsSuccess = isSuccess;
        Error = error;
    }
    
    public static Result<TValue> Success<TValue>(TValue value) =>
        new(value, true);
    
    public static Result<TValue> Failure<TValue>(Error error) =>
        new(default, false, error);
    
    public static implicit operator Result<T>(T? value) =>
        value is not null ? Success(value) : Failure<T>(Error.NullValue);
    
    public TResult Match<TResult>(Func<T, TResult> success, Func<Error, TResult> fail) => IsSuccess ? success(Value!) : fail(Error!);
}
